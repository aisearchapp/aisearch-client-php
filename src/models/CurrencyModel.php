<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class CurrencyModel
 *
 * This model represents currency configuration data.
 * It contains various properties such as the currency code, formatting details, exchange rate,
 * and other flags that control how the currency is displayed.
 */
class CurrencyModel
{
    /**
     * The ISO currency code (e.g., USD, EUR).
     *
     * @var string
     */
    public string $currency_code;

    /**
     * The character used as the decimal point in currency formatting.
     *
     * @var string
     */
    public string $decimal_point;

    /**
     * The character used as the thousands separator in currency formatting.
     *
     * @var string
     */
    public string $thousands_separator;

    /**
     * The symbol representing the currency (e.g., $, €).
     *
     * @var string
     */
    public string $symbol;

    /**
     * The exchange rate of the currency relative to a base currency.
     *
     * @var float
     */
    public float $exchange_rate;

    /**
     * The position of the currency symbol relative to the amount.
     * For example, 0 could indicate symbol on the left and 1 on the right.
     *
     * @var int
     */
    public int $symbol_position;

    /**
     * Flag indicating whether to remove unnecessary trailing zeros after the decimal point.
     *
     * @var bool
     */
    public bool $remove_decimal_zero;

    /**
     * Flag indicating whether the currency is active.
     *
     * @var bool
     */
    public bool $is_active;

    /**
     * Constructor for the CurrencyModel.
     *
     * Initializes the currency model with the provided configuration data.
     *
     * @param string $currency_code The ISO currency code.
     * @param string $decimal_point The decimal point character.
     * @param string $thousands_separator The thousands separator character.
     * @param string $symbol The currency symbol.
     * @param float $exchange_rate The exchange rate relative to a base currency.
     * @param int $symbol_position The position of the symbol relative to the amount.
     * @param bool $remove_decimal_zero Whether to remove trailing zeros in the decimal part.
     * @param bool $is_active Whether the currency is active.
     */
    public function __construct(
        string $currency_code,
        string $decimal_point,
        string $thousands_separator,
        string $symbol,
        float $exchange_rate,
        int $symbol_position,
        bool $remove_decimal_zero,
        bool $is_active
    ) {
        $this->currency_code = $currency_code;
        $this->decimal_point = $decimal_point;
        $this->thousands_separator = $thousands_separator;
        $this->symbol = $symbol;
        $this->exchange_rate = $exchange_rate;
        $this->symbol_position = $symbol_position;
        $this->remove_decimal_zero = $remove_decimal_zero;
        $this->is_active = $is_active;
    }
}
