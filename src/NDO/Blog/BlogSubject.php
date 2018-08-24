<?php
namespace NYPL\Refinery\NDO\Blog;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BlogSubject extends NDO\Term\Subject
{
    use BlogTrait;

    /**
     * @param string $ndoID
     */
    public function setNdoID($ndoID)
    {
        $ndoID = $this->translateFromRelativeUriToId($ndoID);

        parent::setNdoID($ndoID);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = new NDO\TextGroup(new NDO\Text\TextSingle($name));
    }
}
