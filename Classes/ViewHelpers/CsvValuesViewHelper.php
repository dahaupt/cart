<?php

namespace Extcode\Cart\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\CsvUtility;

/**
 * Format array of values to CSV format
 *
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class CsvValuesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Arguments initialization
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument(
            'orderItem',
            \Extcode\Cart\Domain\Model\Order\Item::class,
            'orderItem',
            true
        );
        $this->registerArgument(
            'delim',
            'string',
            'delim',
            false,
            ','
        );
        $this->registerArgument(
            'quote',
            'string',
            'quote',
            false,
            '"'
        );
    }

    /**
     * Format OrderItem to CSV format
     *
     * @return string
     */
    public function render()
    {
        $orderItem = $this->arguments['orderItem'];
        $delim = $this->arguments['delim'];
        $quote = $this->arguments['quote'];

        $orderItemArr = [];

        $orderItemArr[] = $orderItem->getOrderNumber();
        $orderItemArr[] = $orderItem->getOrderDate() ? $orderItem->getOrderDate()->format('d.m.Y') : '';
        $orderItemArr[] = $orderItem->getInvoiceNumber();
        $orderItemArr[] = $orderItem->getInvoiceDate() ? $orderItem->getInvoiceDate()->format('d.m.Y') : '';

        $orderItemArr[] = $orderItem->getBillingAddress()->getSalutation();
        $orderItemArr[] = $orderItem->getBillingAddress()->getTitle();
        $orderItemArr[] = $orderItem->getBillingAddress()->getFirstName();
        $orderItemArr[] = $orderItem->getBillingAddress()->getLastName();

        return CsvUtility::csvValues($orderItemArr, $delim, $quote);
    }
}
