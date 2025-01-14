<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\MessageRepository;
use App\State\MessageListProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
//#[GetCollection(provider: MessageListProvider::class)]
#[GetCollection(normalizationContext: ['groups' => [self::GET_COLLECTION]])]
#[Post(denormalizationContext: ['groups' => [self::POST]], provider: )]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: [
    'conversation'     => 'exact',
])]
class Message
{
    const string GET_COLLECTION = "message:get:collection";
    const string GET = "message:get";
    const string POST = "message:post";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
        Conversation::GET_COLLECTION
    ])]
    private ?int $id = null;


    #[ORM\Column(type: Types::TEXT)]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
    ])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'sentMessage')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
        Conversation::GET_COLLECTION
    ])]
    private ?User $userMessage = null;

    #[ORM\Column]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
    ])]
    private ?\DateTimeImmutable $timeStamp = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
        Conversation::GET_COLLECTION
    ])]
    private ?Rob $rob = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::GET_COLLECTION,
    ])]
    private ?Conversation $conversation = null;

    #[ORM\Column]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
        Conversation::GET_COLLECTION
    ])]
    private ?bool $readed = null;

    #[ORM\Column]
    #[Groups([
        self::GET_COLLECTION,
        Conversation::GET,
        Conversation::GET_COLLECTION
    ])]
    private ?bool $sentByHuman = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getUserMessage(): ?User
    {
        return $this->userMessage;
    }

    public function setUserMessage(?User $userMessage): static
    {
        $this->userMessage = $userMessage;

        return $this;
    }


    public function getTimeStamp(): ?\DateTimeImmutable
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeImmutable $timeStamp): static
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }

    public function getRob(): ?Rob
    {
        return $this->rob;
    }

    public function setRob(?Rob $rob): static
    {
        $this->rob = $rob;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function isReaded(): ?bool
    {
        return $this->readed;
    }

    public function setReaded(bool $readed): static
    {
        $this->readed = $readed;

        return $this;
    }

    public function isSentByHuman(): ?bool
    {
        return $this->sentByHuman;
    }

    public function setSentByHuman(bool $sentByHuman): static
    {
        $this->sentByHuman = $sentByHuman;

        return $this;
    }
}
