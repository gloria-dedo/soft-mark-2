<?php
/**
 * Store category definitions and product matching helpers.
 */

function storeCategories(): array {
    return [
        ['label' => 'All Software', 'slug' => '', 'icon' => 'ti-apps'],
        ['label' => 'Accounting', 'slug' => 'accounting', 'icon' => 'ti-calculator'],
        ['label' => 'HR & Payroll', 'slug' => 'hr', 'icon' => 'ti-users'],
        ['label' => 'Inventory', 'slug' => 'inventory', 'icon' => 'ti-package'],
        ['label' => 'CRM', 'slug' => 'crm', 'icon' => 'ti-heart-handshake'],
        ['label' => 'Procurement', 'slug' => 'procurement', 'icon' => 'ti-shopping-cart'],
        ['label' => 'Projects', 'slug' => 'project', 'icon' => 'ti-layout-kanban'],
        ['label' => 'Support', 'slug' => 'support', 'icon' => 'ti-headset'],
    ];
}

function categoryProductsUrl(string $slug): string {
    return $slug === '' ? 'products.php' : 'products.php?category=' . urlencode($slug);
}

function productMatchesCategory(array $product, string $slug): bool {
    if ($slug === '') {
        return true;
    }

    $text = strtolower(
        ($product['name'] ?? '') . ' ' .
        ($product['description'] ?? '') . ' ' .
        ($product['features'] ?? '')
    );

    $rules = [
        'accounting'  => ['account', 'tax', 'invoice', 'ledger', 'finance'],
        'hr'          => ['hr', 'human', 'payroll', 'employee', 'attendance'],
        'inventory'   => ['inventory', 'stock', 'warehouse', 'supply'],
        'crm'         => ['crm', 'customer', 'lead', 'sales pipeline'],
        'procurement' => ['procurement', 'purchase', 'supplier', 'vendor'],
        'project'     => ['project', 'gantt', 'milestone', 'task'],
        'support'     => ['help', 'support', 'ticket', 'service desk'],
    ];

    if (!isset($rules[$slug])) {
        return false;
    }

    foreach ($rules[$slug] as $keyword) {
        if (strpos($text, $keyword) !== false) {
            return true;
        }
    }

    return false;
}

function filterProductsByCategory(array $products, string $slug): array {
    if ($slug === '') {
        return $products;
    }

    return array_values(array_filter(
        $products,
        fn($p) => productMatchesCategory($p, $slug)
    ));
}

function detectProductCategory(array $product): ?array {
    foreach (storeCategories() as $cat) {
        if ($cat['slug'] !== '' && productMatchesCategory($product, $cat['slug'])) {
            return $cat;
        }
    }

    return null;
}
