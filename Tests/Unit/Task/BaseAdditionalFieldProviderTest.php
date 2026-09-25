<?php

declare(strict_types=1);

namespace Kitodo\Dlf\Tests\Unit\Task;

use Kitodo\Dlf\Task\BaseAdditionalFieldProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class BaseAdditionalFieldProviderTest extends UnitTestCase
{
    #[Test]
    public function nativeEnumEditActionIsRecognized(): void
    {
        $provider = $this->createProvider();

        self::assertTrue($provider->isEditSchedulerActionPublic(DummySchedulerAction::EDIT));
        self::assertFalse($provider->isEditSchedulerActionPublic(DummySchedulerAction::ADD));
    }

    #[Test]
    public function legacyEqualsBasedEditActionIsRecognized(): void
    {
        $provider = $this->createProvider();

        self::assertTrue($provider->isEditSchedulerActionPublic(LegacySchedulerAction::edit()));
        self::assertFalse($provider->isEditSchedulerActionPublic(LegacySchedulerAction::add()));
    }

    private function createProvider(): BaseAdditionalFieldProvider
    {
        return new class extends BaseAdditionalFieldProvider {
            public function isEditSchedulerActionPublic(mixed $action): bool
            {
                return $this->isEditSchedulerAction($action);
            }
        };
    }
}

enum DummySchedulerAction: string
{
    case ADD = 'add';
    case EDIT = 'edit';
}

final class LegacySchedulerAction
{
    public const ADD = 'add';
    public const EDIT = 'edit';

    private function __construct(private readonly string $value)
    {
    }

    public static function add(): self
    {
        return new self(self::ADD);
    }

    public static function edit(): self
    {
        return new self(self::EDIT);
    }

    public function equals(string $other): bool
    {
        return $this->value === $other;
    }
}
