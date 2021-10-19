#############################
###### EXEMPLE D'USAGE ######
#############################

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @Uploadable()
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @UploadableField(filename="filename", path="uploads")
     * @Assert\Image(maxWidth="2000", maxHeight="2000")
     */
    private $file;

    /**
     * @UploadableField(filename="avatar", path="avatars")
     */
    private $avatarFile;

    /**
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Post
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Post
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file|null
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Post
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return mixed
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param mixed $avatar_file
     */
    public function setAvatarFile($avatar_file)
    {
        $this->avatarFile = $avatar_file;
    }
}

############################################
### CONFIGURATION ###
############################################

### config/services.yaml ###

upload.annotation_reader:
        class: App\CoelacantheLabs\Annotation\UploadAnnotationReader
        arguments: [ '@annotations.reader' ]

    aupload.handler:
        class: App\CoelacantheLabs\Listener\UploadSubscriber
        arguments: [ "@upload.annotation_reader" ]
        tags:
            - { name: doctrine.event_subscriber, connection: default }

### config/bundles.php ###
App\CoelacantheLabs\UploadBundle::class => ['all' => true]