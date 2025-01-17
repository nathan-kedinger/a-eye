<?php
namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ConversationRepository;
use App\State\ConversationProcessor;
use App\State\ConversationProvider;
use App\State\MessageProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[Get(normalizationContext: ['groups' => [self::GET]], provider: ConversationProvider::class )]
#[GetCollection(normalizationContext: ['groups' => [self::GET_COLLECTION]], provider: ConversationProvider::class )]
#[Post(denormalizationContext: ['groups' => [self::POST]], processor: ConversationProcessor::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: [
    'rob.id'     => 'exact',
])]
class Conversation
{
    const string GET_COLLECTION = 'conversation:get:collection';
    const string GET = 'conversation:get';
    const string POST = 'conversation:post';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        self::GET_COLLECTION,
        self::GET
    ])]
    private ?int $id = null;


    #[Groups([
        self::GET_COLLECTION,
        self::GET,
        self::POST
    ])]
    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups([
        self::GET_COLLECTION,
        self::GET,
        self::POST

    ])]
    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rob $rob = null;

    #[Groups([
        self::GET_COLLECTION,
        self::GET,
        self::POST

    ])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups([
        self::GET_COLLECTION,
        self::GET,
        self::POST

    ])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Message>
     */
    #[Groups([
        self::GET_COLLECTION,
        self::GET,
        self::POST

    ])]
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'conversation')]
    private Collection $messages;

    #[Groups([
        self::GET_COLLECTION,
        self::GET,
        self::POST

    ])]
    #[ORM\Column(type: "datetime_immutable")]
    private ?\DateTimeImmutable $lastUpdate = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeImmutable
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeImmutable $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }
}
