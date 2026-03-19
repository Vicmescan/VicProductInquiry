import Plugin from 'src/plugin-system/plugin.class';
import { Modal } from 'bootstrap';

export default class VicInquiryModalPlugin extends Plugin {

    init() {
        const productId = this.el.dataset.productId;
        const modalElement = document.getElementById(`vicInquiryModal--${productId}`);

        if (!modalElement) {
            return;
        }

        this._modal      = new Modal(modalElement);
        this._dailyPrice = parseFloat(this.el.dataset.dailyPrice) || 0;

        this.el.addEventListener('click', () => this._modal.show());
        this._bindDateEvents(modalElement);
    }

    _bindDateEvents(modalElement) {
        this._startInput = modalElement.querySelector('.vic-inquiry-date-start');
        this._endInput   = modalElement.querySelector('.vic-inquiry-date-end');
        this._summary    = modalElement.querySelector('.vic-inquiry-price-summary');

        if (!this._startInput || !this._endInput) return;

        // Fecha mínima de inicio: hoy
        const today = new Date().toISOString().split('T')[0];
        this._startInput.min = today;

        this._startInput.addEventListener('change', () => this._onDateChange());
        this._endInput.addEventListener('change',   () => this._onDateChange());
    }

    _onDateChange() {
        const start = this._startInput.value;
        const end   = this._endInput.value;

        // Al elegir inicio, la fecha fin no puede ser anterior
        if (start) {
            this._endInput.min = start;
        }

        if (!start || !end || end <= start) {
            this._summary.classList.add('d-none');
            return;
        }

        // Diferencia en días entre las dos fechas
        const msPerDay = 1000 * 60 * 60 * 24;
        const days     = Math.round((new Date(end) - new Date(start)) / msPerDay);
        const total    = days * this._dailyPrice;

        // Formateamos con el locale de la página y la moneda del producto
        const locale   = document.documentElement.lang || 'de-DE';
        const currency = document.querySelector('meta[itemprop="priceCurrency"]')?.content || 'EUR';
        const fmt      = new Intl.NumberFormat(locale, { style: 'currency', currency });

        // Actualizamos el resumen visual
        this._summary.querySelector('.vic-inquiry-days-display').textContent  = days;
        this._summary.querySelector('.vic-inquiry-daily-display').textContent = fmt.format(this._dailyPrice);
        this._summary.querySelector('.vic-inquiry-total-display').textContent = fmt.format(total);
        this._summary.classList.remove('d-none');

        // Rellenamos los inputs hidden que enviará el formulario
        const form = this._startInput.closest('form');
        form.querySelector('.vic-inquiry-start-date').value  = start;
        form.querySelector('.vic-inquiry-end-date').value    = end;
        form.querySelector('.vic-inquiry-rental-days').value = days;
        form.querySelector('.vic-inquiry-total-price').value = total.toFixed(2);
    }
}
