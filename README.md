# WooCommerce Product Handler

A powerful and flexible PHP class for handling WooCommerce product retrieval with various filters and sorting options.

## Features
- Fetch products based on category, price, stock status, and attributes.
- Supports custom sorting (popular, price ascending/descending, date).
- Provides easy pagination and query management.

## Installation

Clone the repository to your project:

```bash
git clone https://github.com/abolfazl-mahjoob/WooCommerce-Product-Handler.git
```

Then, include the class in your theme or plugin:
require_once 'path/to/A_MR/Handler/GetProducts.php';

## Usage
Basic Example
Here’s a simple example to fetch products by category and display them:

```bash
use A_MR\GetProducts;

// Initialize the class with a category ID (optional)
$product_handler = new GetProducts(15); // 15 is the term ID for a specific category

// Set pagination options
$product_handler->set_current_page(1);
$product_handler->set_post_per_page(10);

// Get products
$products = $product_handler->get_products();

if ($products->have_posts()) {
    while ($products->have_posts()) {
        $products->the_post();
        // Output product information here
    }
    wp_reset_postdata();
}

Applying Filters
You can set filters through URL parameters:

is_stock: To get products that are in stock.
min_price and max_price: To filter by price range.
pa_{attribute_name}: To filter by product attributes.
Example URL:
/your-page/?is_stock=1&min_price=50&max_price=300&pa_color=12

Sorting Options
Sort products using the following query parameters:

sort=popular - Sort by popularity.
sort=price_c - Sort by price (ascending).
sort=price_e - Sort by price (descending).
sort=date - Sort by date (newest first).
```
## Methods
set_current_page(int $page_number): void
Sets the current page for pagination.

set_post_per_page(int $count): void
Defines how many products should be displayed per page.

get_products(): WP_Query
Returns a WP_Query object with the products based on the specified filters.

get_pagination(): array
Returns an array containing the current and total number of pages for pagination.

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Contribution
Feel free to open issues, submit pull requests, or fork the project to add new features. Contributions are highly appreciated!

## Author
Created by Abolfazl Mahjoob. If you find this project useful, consider starring the repository 🌟.

Enjoy using WooCommerce Product Handler for your projects, and happy coding! 🚀


### توضیحات بخش‌ها

1. **Features**: به ویژگی‌های کلاس اشاره می‌کند.
2. **Installation**: راهنمای نصب و استفاده.
3. **Usage**: مثال‌هایی از نحوه استفاده از کلاس.
4. **Methods**: توضیح مختصر برای هر متد.
5. **License**: اشاره به لایسنس و فایل آن.
6. **Contribution**: تشویق به همکاری و مشارکت.
7. **Author**: معرفی نویسنده پروژه و لینک به حساب GitHub شما.

