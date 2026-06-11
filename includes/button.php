<?php

/**
 * Reusable pill button — matches product card "Add To Cart" styling.
 *
 * @param array{
 *   label: string,
 *   href?: string|null,
 *   type?: string,
 *   variant?: string,
 *   icon?: string|null,
 *   iconRight?: string|null,
 *   class?: string,
 *   size?: string,
 *   block?: bool,
 *   disabled?: bool,
 *   id?: string|null,
 *   attrs?: array<string, string|int|bool>
 * } $options
 */
function renderButton(array $options): string
{
    $label = trim($options['label'] ?? '');
    if ($label === '') {
        return '';
    }

    $href     = $options['href'] ?? null;
    $type     = $options['type'] ?? 'submit';
    $variant  = $options['variant'] ?? 'primary';
    $icon     = $options['icon'] ?? null;
    $iconRight = $options['iconRight'] ?? null;
    $extra    = trim($options['class'] ?? '');
    $size     = trim($options['size'] ?? '');
    $block    = !empty($options['block']);
    $disabled = !empty($options['disabled']);
    $id       = $options['id'] ?? null;
    $attrs    = $options['attrs'] ?? [];

    $classes = ['btn', 'btn-' . $variant];
    if ($size !== '') {
        $classes[] = 'btn-' . $size;
    }
    if ($block) {
        $classes[] = 'btn-block';
    }
    if ($extra !== '') {
        $classes[] = $extra;
    }

    $attrHtml = '';
    if ($id) {
        $attrHtml .= ' id="' . htmlspecialchars((string) $id) . '"';
    }
    foreach ($attrs as $key => $value) {
        if ($value === false || $value === null) {
            continue;
        }
        if ($value === true) {
            $attrHtml .= ' ' . htmlspecialchars((string) $key);
            continue;
        }
        $attrHtml .= ' ' . htmlspecialchars((string) $key) . '="' . htmlspecialchars((string) $value) . '"';
    }
    if ($disabled) {
        $attrHtml .= ' disabled';
    }

    $content = '';
    if ($icon) {
        $content .= '<i class="' . htmlspecialchars($icon) . '" aria-hidden="true"></i>';
    }
    $content .= '<span>' . htmlspecialchars($label) . '</span>';
    if ($iconRight) {
        $content .= '<i class="' . htmlspecialchars($iconRight) . '" aria-hidden="true"></i>';
    }

    $classStr = htmlspecialchars(implode(' ', $classes));

    if ($href !== null && $href !== '' && !$disabled) {
        return '<a href="' . htmlspecialchars($href) . '" class="' . $classStr . '"' . $attrHtml . '>' . $content . '</a>';
    }

    return '<button type="' . htmlspecialchars($type) . '" class="' . $classStr . '"' . $attrHtml . '>' . $content . '</button>';
}
