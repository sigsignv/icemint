<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Routing;

use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RouterInterface as BaseRouterInterface;

interface RouterInterface extends BaseRouterInterface, RequestMatcherInterface {}
