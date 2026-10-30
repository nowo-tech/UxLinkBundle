<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model;

/**
 * Immutable, iterable collection of links.
 *
 * @implements \IteratorAggregate<int, Link>
 */
final class LinkCollection implements \Countable, \IteratorAggregate
{
    /**
     * Creates an immutable collection of links.
     *
     * @param list<Link> $links
     */
    public function __construct(
        private array $links = [],
    ) {
    }

    /**
     * Returns a copy of the collection with the link appended.
     *
     * @param Link $link Link to append
     */
    public function add(Link $link): self
    {
        $clone = clone $this;
        $clone->links[] = $link;

        return $clone;
    }

    /**
     * Returns the links in insertion order.
     *
     * @return list<Link>
     */
    public function all(): array
    {
        return $this->links;
    }

    /**
     * Returns the number of links.
     */
    public function count(): int
    {
        return \count($this->links);
    }

    /**
     * Iterates over the links in insertion order.
     *
     * @return \Traversable<int, Link>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->links;
    }
}
