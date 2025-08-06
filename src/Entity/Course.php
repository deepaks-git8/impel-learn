<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Course name is required.')]
    #[Assert\Regex(
        pattern: '/[A-Za-z]/',
        message: 'Course name must contain at least one letter.'
    )]
    #[Assert\Regex(
        pattern: '/^[^\d\.]/',
        message: 'Course name cannot start with a number or a period.'
    )]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Course name cannot be shorter than {{ limit }} characters.',
        maxMessage: 'Course name cannot be longer than {{ limit }} characters.'
    )]

    #[Assert\Regex(
        pattern: '/^[^\d]/',
        message: 'Course name cannot start with a number.'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-z0-9\s\-_,\.;:()]+$/',
        message: 'Course name contains invalid characters.'
    )]
    private ?string $name = null;


    #[ORM\OneToMany(targetEntity: Enrollment::class, mappedBy: 'course', orphanRemoval: true)]
    private Collection $enrollments;

    #[ORM\Column(length: 10000)]
    #[Assert\NotBlank(message: 'Description is required.')]
    #[Assert\Length(
        min: 50,
        max: 500,
        minMessage: 'Description cannot be shorter than {{ limit }} characters.',
        maxMessage: 'Description cannot be longer than {{ limit }} characters.'

    )]
    #[Assert\Regex(
        pattern: '/^[^<>]*$/',
        message: 'Course description cannot contain HTML tags.'
    )]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Instructor is required.')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Instructor name must be at least {{ limit }} characters long.',
        maxMessage: 'Instructor name cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-z\s\-\.]+$/',
        message: 'Instructor name can only contain letters, spaces, hyphens, and periods.'
    )]
    #[Assert\Regex(
        pattern: '/^[^\d]/',
        message: 'Instructor name cannot start with a number.'
    )]
    #[Assert\Regex(
        pattern: '/^[^\d-]/',
        message: 'Instructor name cannot start with a number or a hyphen.'
    )]

    #[Assert\Regex(
        pattern: '/[A-Za-z]/',
        message: 'Instructor name must contain at least one letter.'
    )]
    #[Assert\Regex(
        pattern: '/^[^\d\.]/',
        message: 'Instructor name cannot start with a number or a period.'
    )]
    private ?string $instructor = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Duration is required.')]
    private ?string $duration = null;

    public function __construct()
    {
        $this->enrollments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Enrollment>
     */
    public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }

    public function addEnrollment(Enrollment $enrollment): static
    {
        if (!$this->enrollments->contains($enrollment)) {
            $this->enrollments->add($enrollment);
            $enrollment->setCourse($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): static
    {
        if ($this->enrollments->removeElement($enrollment)) {
            if ($enrollment->getCourse() === $this) {
                $enrollment->setCourse(null);
            }
        }

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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getInstructor(): ?string
    {
        return $this->instructor;
    }

    public function setInstructor(?string $instructor): static
    {
        $this->instructor = $instructor;
        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;
        return $this;
    }
}
