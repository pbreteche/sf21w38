<?php

namespace App\Entity;

use App\Repository\PostRepository;
use App\Validator\Even;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[^$]+$/", message="Le caractère ""$"" est interdit")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=10, groups="Edit")
     */
    private $body;

    /**
     * @ORM\Column(type="boolean")
     * @Even()
     */
    private $isPublished;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotNull(message="La date de création doit avoir été définie automatiquement")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $writtenBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @Assert\GreaterThan(value="20", message="total_text_length.message")
     */
    public function getTotalTextLength()
    {
        return mb_strlen($this->title)+mb_strlen($this->body);
    }

    /**
     * @Assert\Callback()
     */
    public function totalTextLengthValidate(ExecutionContextInterface $context, $payload)
    {
        $totalLength = mb_strlen($this->title)+mb_strlen($this->body);

        if (20 > $totalLength) {
            $context->buildViolation('total_text_length.message')
                ->atPath('title')
                ->addViolation()
            ;
        }
    }

    public function getWrittenBy(): ?Author
    {
        return $this->writtenBy;
    }

    public function setWrittenBy(?Author $writtenBy): self
    {
        $this->writtenBy = $writtenBy;

        return $this;
    }
}
