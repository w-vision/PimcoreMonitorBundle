<?php

declare(strict_types=1);

/**
 * Pimcore Monitor
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2022 w-vision AG (https://www.w-vision.ch)
 * @license    https://github.com/w-vision/PimcoreMonitorBundle/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Wvision\Bundle\PimcoreMonitorBundle\Check;

use Laminas\Diagnostics\Result\ResultInterface;
use Laminas\Diagnostics\Result\Skip;
use Laminas\Diagnostics\Result\Success;
use Pimcore\Extension\Document\Areabrick\AreabrickManagerInterface;

class PimcoreAreabricks extends AbstractCheck
{
    protected const IDENTIFIER = 'pimcore:areabricks';

    protected bool $skip;
    protected AreabrickManagerInterface $areabrickManager;

    public function __construct(bool $skip, AreabrickManagerInterface $areabrickManager)
    {
        $this->skip = $skip;
        $this->areabrickManager = $areabrickManager;
    }

    public function check(): ResultInterface
    {
        if ($this->skip) {
            return new Skip('Check was skipped');
        }

        $bricks = [];

        foreach ($this->areabrickManager->getBricks() as $brickId => $brick) {
            $bricks[] = [
                'identifier' => $brickId,
                'name' => $brick->getName(),
                'description' => $brick->getDescription(),
                'is_enabled' => $this->areabrickManager->isEnabled($brickId),
            ];
        }

        return new Success(sprintf('There are %s Areabricks in the system', \count($bricks)), $bricks);
    }

    public function getLabel(): string
    {
        return 'Pimcore Areabricks';
    }
}
