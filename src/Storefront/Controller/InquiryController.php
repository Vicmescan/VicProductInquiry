<?php declare(strict_types=1);

namespace Vic\ProductInquiry\Storefront\Controller;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class InquiryController extends StorefrontController
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly SystemConfigService $systemConfigService,
        private readonly EntityRepository $inquiryRepository,
    ) {
    }

    #[Route(
        path: '/vic/product-inquiry/send',
        name: 'vic.product.inquiry.send',
        methods: ['POST']
    )]
    public function send(Request $request, SalesChannelContext $context): Response
    {
        $customerName  = trim((string) $request->request->get('customerName', ''));
        $customerEmail = trim((string) $request->request->get('customerEmail', ''));
        $message       = trim((string) $request->request->get('message', ''));
        $productName   = trim((string) $request->request->get('productName', ''));
        $productId     = trim((string) $request->request->get('productId', ''));

        // Campos de alquiler — opcionales, solo llegan si el JS los calculó
        $startDate   = trim((string) $request->request->get('startDate', ''));
        $endDate     = trim((string) $request->request->get('endDate', ''));
        $rentalDays  = (int) $request->request->get('rentalDays', 0);
        $totalPrice  = (float) $request->request->get('totalPrice', 0);

        if ($customerName === '' || $customerEmail === '' || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash(self::DANGER, $this->trans('vic.productInquiry.flash.error'));
            return $this->redirectToRoute('frontend.detail.page', ['productId' => $productId]);
        }

        $this->inquiryRepository->create([[
            'id'            => Uuid::randomHex(),
            'productId'     => $productId,
            'productName'   => $productName,
            'customerName'  => $customerName,
            'customerEmail' => $customerEmail,
            'message'       => $message ?: null,
            'startDate'     => $startDate  ?: null,
            'endDate'       => $endDate    ?: null,
            'rentalDays'    => $rentalDays ?: null,
            'totalPrice'    => $totalPrice ?: null,
        ]], $context->getContext());

        $salesChannelId = $context->getSalesChannelId();
        $recipientEmail = $this->systemConfigService->getString(
            'VicProductInquiry.config.recipientEmail', $salesChannelId
        ) ?: $this->systemConfigService->getString(
            'core.basicInformation.email', $salesChannelId
        );

        $subjectPrefix = $this->systemConfigService->getString(
            'VicProductInquiry.config.emailSubjectPrefix', $salesChannelId
        ) ?: 'Product inquiry';

        // Construimos el cuerpo del email incluyendo el periodo de alquiler si existe
        $rentalInfo = '';
        if ($startDate && $endDate && $rentalDays > 0) {
            $rentalInfo = sprintf(
                "\n\nRental period: %s → %s (%d days)\nEstimated total: %.2f €",
                $startDate, $endDate, $rentalDays, $totalPrice
            );
        }

        $email = (new Email())
            ->from($customerEmail)
            ->to($recipientEmail)
            ->replyTo($customerEmail)
            ->subject(sprintf('%s: %s', $subjectPrefix, $productName))
            ->text(sprintf(
                "New product inquiry\n\nProduct: %s\nName: %s\nEmail: %s%s\n\nMessage:\n%s",
                $productName, $customerName, $customerEmail,
                $rentalInfo,
                $message ?: '(no message)'
            ));

        $this->mailer->send($email);

        $this->addFlash(self::SUCCESS, $this->trans('vic.productInquiry.flash.success'));

        return $this->redirectToRoute('frontend.detail.page', ['productId' => $productId]);
    }
}
