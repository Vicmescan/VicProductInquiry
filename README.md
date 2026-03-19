# VicProductInquiry — Shopware 6.7 Plugin

A Shopware 6.7 plugin that replaces the add-to-cart button with a rental inquiry form for selected products. Ideal for rental shops (vehicles, equipment, spaces) where the customer needs to check availability before purchasing.

---

## Features

- **Per-product activation** via a custom field — enable inquiry mode on any product without affecting the rest of the catalogue
- **Rental date picker** — customer selects a start and end date directly in the modal
- **Live price calculation** — total is calculated in real time based on the rental period and the product's price (price × days)
- **Email notification** — the shop owner receives a formatted email with all inquiry details
- **Inquiry log** — every submission is saved to a dedicated database table for future reference
- **Admin listing** — consult all inquiries from the Shopware administration under Catalogue → Product Inquiries
- **Plugin configuration** — set a custom recipient email and email subject prefix from the admin panel
- **Multilingual** — snippets included for English, Spanish and German

---

## Screenshots

> *(Add screenshots here)*

---

## Requirements

- Shopware 6.7.x
- PHP 8.2+

---

## Installation

1. Clone or download this repository into your Shopware installation:
   ```bash
   cd custom/plugins
   git clone git@github.com:Vicmescan/VicProductInquiry.git
   ```

2. Refresh and install the plugin:
   ```bash
   bin/console plugin:refresh
   bin/console plugin:install VicProductInquiry --activate
   bin/console cache:clear
   ```

3. Build the storefront and administration assets:
   ```bash
   ./bin/build-storefront.sh
   ./bin/build-administration.sh
   bin/console cache:clear
   ```

4. Run database migrations:
   ```bash
   bin/console database:migrate-destructive --all VicProductInquiry
   ```

---

## Configuration

### Activate inquiry mode on a product

1. Go to **Catalogues → Products** and open any product
2. Scroll to the **Custom fields** section
3. Enable **"Activate inquiry mode"**
4. Save the product

The add-to-cart button will be replaced by the inquiry button on that product's detail page.

### Plugin settings

Go to **Settings → System → Plugins → VicProductInquiry**:

| Setting | Description |
|---|---|
| Recipient email | Email address to receive inquiries. Leave empty to use the shop's default contact email |
| Email subject prefix | Prefix added to the inquiry email subject. Default: `Product inquiry` |

---

## How it works

1. Customer visits a product with inquiry mode active
2. The add-to-cart button is replaced by **"Ask for availability"**
3. A Bootstrap modal opens with a form: name, email, rental dates and optional message
4. As the customer selects dates, the estimated total is calculated live (product price × number of days)
5. On submission:
   - The inquiry is saved to the `vic_product_inquiry` table
   - An email is sent to the shop owner with all details
6. The customer sees a success message and is redirected back to the product page

---

## Database

The plugin creates a `vic_product_inquiry` table with the following fields:

| Field | Type | Description |
|---|---|---|
| id | BINARY(16) | UUID primary key |
| product_id | VARCHAR(255) | Shopware product UUID |
| product_name | VARCHAR(255) | Product name at time of inquiry |
| customer_name | VARCHAR(255) | Customer name |
| customer_email | VARCHAR(255) | Customer email |
| message | LONGTEXT | Optional message |
| start_date | DATE | Rental start date |
| end_date | DATE | Rental end date |
| rental_days | INT | Number of rental days |
| total_price | DECIMAL(10,2) | Estimated total price |
| created_at | DATETIME | Submission date |

---

## Development

Built with:
- **PHP 8.3** — plugin backend, controller, migrations, DAL entities
- **Twig** — storefront template overrides
- **Vanilla JS** — storefront plugin (date picker, live price calculation)
- **Vue.js** — Shopware administration module
- **Bootstrap 5** — modal and form styling (provided by Shopware storefront)

---

## License

MIT — feel free to use, modify and distribute.
