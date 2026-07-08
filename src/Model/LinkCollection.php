<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model;

/**
 * @implements \IteratorAggregate<int, Link>
 */
final class LinkCollection implements \Countable, \IteratorAggregate
{
    /**
     * @param list<Link> $links
     */
    public function __construct(
        private array $links = [],
    ) {
    }

    public function add(Link $link): self
    {
        $clone = clone $this;
        $clone->links[] = $link;

        return $clone;
    }

    /**
     * @return list<Link>
     */
    public function all(): array
    {
        return $this->links;
    }

    public function count(): int
    {
        return \count($this->links);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->links;
    }
}
