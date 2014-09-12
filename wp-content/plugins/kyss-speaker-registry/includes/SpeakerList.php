<?php
/**
 * Created by PhpStorm.
 * User: mikelhensley
 * Date: 9/9/14
 * Time: 12:35 PM
 *
 */

namespace kyss;


use Exception;

class Speaker {

    private $speakerId          =   0; /*unique identifier*/
    private $speakerName        =   "";
    private $speakerBio         =   ""; /*multiline text*/
    private $speakerEmail       =   ""; /*not to be displayed directly to users. Use for contact form.*/
    private $speakerTopics      =   "";
    private $speakerPictureUrl  =   ""; /*should be a relative link to the images folder*/
    private $speakerLinks       =   ""; /*make this an array or a list*/
    private $speakerHomeCity    =   "";
    private $speakerHomeState   =   "";

    /**
     * @param string $speakerBio
     */
    public function setSpeakerBio($speakerBio)
    {
        $this->speakerBio = $speakerBio;
    }

    /**
     * @param string $speakerEmail
     */
    public function setSpeakerEmail($speakerEmail)
    {
        $this->speakerEmail = $speakerEmail;
    }

    /**
     * @param string $speakerHomeCity
     */
    public function setSpeakerHomeCity($speakerHomeCity)
    {
        $this->speakerHomeCity = $speakerHomeCity;
    }

    /**
     * @param string $speakerHomeState
     */
    public function setSpeakerHomeState($speakerHomeState)
    {
        $this->speakerHomeState = $speakerHomeState;
    }

    /**
     * @param string $speakerLinks
     */
    public function setSpeakerLinks($speakerLinks)
    {
        $this->speakerLinks = $speakerLinks;
    }

    /**
     * @param string $speakerName
     */
    public function setSpeakerName($speakerName)
    {
        $this->speakerName = $speakerName;
    }

    /**
     * @param string $speakerPictureUrl
     */
    public function setSpeakerPictureUrl($speakerPictureUrl)
    {
        $this->speakerPictureUrl = $speakerPictureUrl;
    }

    /**
     * @param string $speakerTopics
     */
    public function setSpeakerTopics($speakerTopics)
    {
        $this->speakerTopics = $speakerTopics;
    }


    /**
     * @return string
     */
    public function getSpeakerBio()
    {
        return $this->speakerBio;
    }

    /**
     * @return string
     */
    public function getSpeakerEmail()
    {
        return $this->speakerEmail;
    }

    /**
     * @return string
     */
    public function getSpeakerHomeCity()
    {
        return $this->speakerHomeCity;
    }

    /**
     * @return string
     */
    public function getSpeakerHomeState()
    {
        return $this->speakerHomeState;
    }

    /**
     * @return int
     */
    public function getSpeakerId()
    {
        return $this->speakerId;
    }

    /**
     * @return string
     */
    public function getSpeakerLinks()
    {
        return $this->speakerLinks;
    }

    /**
     * @return string
     */
    public function getSpeakerName()
    {
        return $this->speakerName;
    }

    /**
     * @return string
     */
    public function getSpeakerPictureUrl()
    {
        return $this->speakerPictureUrl;
    }

    /**
     * @return string
     */
    public function getSpeakerTopics()
    {
        return $this->speakerTopics;
    } /*make these keywords*/


    function __construct()
    {
        #$this->speakerId = $speakerId;
    }

    function insert(){
        throw new Exception ("This method is not yet implemented.");
    }

    function update(){
        throw new Exception ("This method is not yet implemented.");
    }

    function remove(){
        throw new Exception ("This method is not yet implemented.");
    }

} 