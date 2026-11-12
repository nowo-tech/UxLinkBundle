<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration\Worker;

use Nowo\UxLinkBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

/**
 * REQ-RUNTIME-002: ONE production-like kernel (debug off), `services_resetter` / `kernel.reset` never called (FrankenPHP worker, kernel reset
 * false). N iterations alternate two users (own recipient, label, message, share URL): the same kept Twig environment
 * renders `ux_link()`, `ux_link_url()`, `ux_links()` and the non-shared `UxLink` component. Each output must carry only
 * its own data (the component must not keep its previous link) and memory must stay flat.
 */
#[CoversNothing]
final class WorkerKeptAliveKernelTest extends KernelTestCase
{
    private const ITERATIONS = 1200;
    private const WARM_UP = 100;
    private const MARGIN = 4 * 1024 * 1024;

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testAlternatingUsersInOneKernelLeakNothingAndKeepMemoryFlat(): void
    {
        self::bootKernel(['debug' => false]);
        /** @var Environment $twig */
        $twig = self::getContainer()->get('twig');
        $template = $twig->createTemplate(
            "{{ ux_link('contact', 'email', {recipient: mail, subject: subject, label: label}) }}\n"
            ."{{ ux_link_url('contact', 'email', {recipient: mail}) }}\n"
            ."{{ ux_links('contact', ['email'], {recipient: mail, label: label}) }}\n"
            ."{{ component('UxLink', {family: 'contact', provider: 'email', recipient: mail, label: label, class: css}) }}"
        );

        $users = [
            'alpha' => ['mail' => 'alpha.user@example.test', 'subject' => 'alpha-subject', 'label' => 'Alpha Label', 'css' => 'alpha-css'],
            'beta' => ['mail' => 'beta.user@example.test', 'subject' => 'beta-subject', 'label' => 'Beta Label', 'css' => 'beta-css'],
        ];

        $baseline = 0;
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            if (self::WARM_UP === $i) {
                \gc_collect_cycles();
                $baseline = \memory_get_usage();
            }

            $who = 0 === $i % 2 ? 'alpha' : 'beta';
            $other = 'alpha' === $who ? 'beta' : 'alpha';
            $data = $users[$who];

            $html = $template->render($data);

            self::assertStringContainsString('mailto:'.$data['mail'], $html, \sprintf('iteration %d', $i));
            self::assertStringContainsString($data['label'], $html, \sprintf('iteration %d', $i));
            self::assertStringContainsString($data['css'], $html, \sprintf('iteration %d', $i));
            self::assertStringContainsString($data['subject'], $html, \sprintf('iteration %d', $i));
            foreach ($users[$other] as $value) {
                self::assertFalse(\str_contains($html, $value), \sprintf('iteration %d: "%s" of the other user in output', $i, $value));
            }
        }

        \gc_collect_cycles();
        $growth = \memory_get_usage() - $baseline;
        self::assertLessThan(self::MARGIN, $growth, \sprintf('memory grew by %d bytes over %d iterations', $growth, self::ITERATIONS - self::WARM_UP));
    }
}
