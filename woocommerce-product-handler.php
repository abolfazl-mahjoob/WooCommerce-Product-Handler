<?php

namespace A_MR;

use WP_Query;

class GetProducts
{
    private array $query_args = [];
    private int $per_page = 10;
    private int $current_page = 1;
    private WP_Query $query;

    public function set_current_page($current): void
    {
        $this->current_page = $current;
    }

    public function set_post_per_page($count): void
    {
        $this->per_page = $count;
        $this->query_args['posts_per_page'] = $count;
    }

    public function __construct($term_id = null)
    {
        $this->query_args = [
            'post_type' => 'product',
            'posts_per_page' => $this->per_page,
            'paged' => $this->current_page,
        ];

        if ($term_id !== null) {
            $this->query_args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $term_id,
                ]
            ];
        }

        $this->apply_filters();
    }

    private function apply_filters(): void
    {
        if (isset($_GET['is_stock'])) {
            $this->query_args['meta_query'][] = [
                'key' => '_stock_status',
                'value' => 'instock'
            ];
        }

        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $max_price = isset($_GET['max_price']) && $_GET['max_price'] ? floatval($_GET['max_price']) : PHP_INT_MAX;

            $this->query_args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => '_price',
                    'value' => [$min_price, $max_price],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ],
                [
                    'key' => '_min_variation_price',
                    'value' => [$min_price, $max_price],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ]
            ];
        }

        foreach ($_GET as $key => $value) {
            if (str_starts_with($key, 'pa_') && !empty($value)) {
                $this->query_args['tax_query'][] = [
                    'taxonomy' => $key,
                    'field' => 'term_id',
                    'terms' => $value
                ];
            }
        }

        $this->apply_sorting();
    }

    private function apply_sorting(): void
    {
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'popular':
                    $this->query_args['orderby'] = 'meta_value_num';
                    $this->query_args['meta_key'] = 'total_sales';
                    $this->query_args['order'] = 'DESC';
                    break;
                case 'price_e':
                    $this->query_args['orderby'] = 'meta_value_num';
                    $this->query_args['meta_key'] = '_price';
                    $this->query_args['order'] = 'DESC';
                    break;
                case 'price_c':
                    $this->query_args['orderby'] = 'meta_value_num';
                    $this->query_args['meta_key'] = '_price';
                    $this->query_args['order'] = 'ASC';
                    break;
                case 'date':
                default:
                    $this->query_args['orderby'] = 'date';
                    $this->query_args['order'] = 'DESC';
                    break;
            }
        } else {
            $this->query_args['orderby'] = 'date';
            $this->query_args['order'] = 'DESC';
        }
    }

    public function get_products(): WP_Query
    {
        $query = new WP_Query($this->query_args);
        $this->query = $query;
        return $query;
    }

    public function get_pagination(): array
    {
        $total_pages = ceil($this->query->found_posts / $this->per_page);
        return [
            'current_page' => $this->current_page,
            'total_pages' => $total_pages
        ];
    }
}
