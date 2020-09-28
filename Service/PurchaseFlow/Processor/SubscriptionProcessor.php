<?php
/**
 * This file is part of SocialLogin4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\payjp4\Service\PurchaseFlow\Processor;


use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\Processor\AbstractPurchaseProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;

class SubscriptionProcessor extends AbstractPurchaseProcessor
{
    public function prepare(ItemHolderInterface $target, PurchaseContext $context): void
    {
        parent::prepare($target, $context); // TODO: Change the autogenerated stub
    }

    public function commit(ItemHolderInterface $target, PurchaseContext $context): void
    {
        parent::commit($target, $context); // TODO: Change the autogenerated stub
    }

    public function rollback(ItemHolderInterface $itemHolder, PurchaseContext $context): void
    {
        parent::rollback($itemHolder, $context); // TODO: Change the autogenerated stub
    }
}
