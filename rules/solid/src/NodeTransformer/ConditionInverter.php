<?php

declare(strict_types=1);

namespace Rector\SOLID\NodeTransformer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BooleanNot;
use Rector\Core\PhpParser\Node\Manipulator\BinaryOpManipulator;

final class ConditionInverter
{
    /**
     * @var BinaryOpManipulator
     */
    private $binaryOpManipulator;

    public function __construct(BinaryOpManipulator $binaryOpManipulator)
    {
        $this->binaryOpManipulator = $binaryOpManipulator;
    }

    public function createInvertedCondition(Expr $expr): Expr
    {
        // inverse condition
        if ($expr instanceof BinaryOp) {
            $inversedCondition = $this->binaryOpManipulator->invertCondition($expr);

            // When calling invertCondition() with a BooleanOr, it returns a BooleanAnd that needs a BooleanNot added.
            if ($inversedCondition === null || $inversedCondition instanceof BooleanAnd) {
                return new BooleanNot($expr);
            }

            return $inversedCondition;
        }

        return new BooleanNot($expr);
    }
}
