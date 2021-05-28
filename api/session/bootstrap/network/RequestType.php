<?php


namespace session;


class RequestType
{
    const AUTH = "AUTH";
    /**
     *
     *
     *
     * Media types
     *
     *
     */
    const MEDIA_ALL = 1;
    const MEDIA_FIND = 2;
    const MEDIA_DELETE = 3;
    const MEDIA_ADD = 4;
    const DOWNLOAD = 6;
    const MEDIA_EDIT = 5;
    const MEDIA_CATEGORY = 7;
    const UPLOAD = 8;


    /**
     *
     *
     *
     * Content types
     *
     *
     */

    const MODULE_ALL = 1;
    const MODULE_FIND = 2;
    const MODULE_DELETE = 3;
    const MODULE_ADD = 4;
    const MODULE_EDIT = 5;
    const MODULE_SUBJECT = 7;
    const MODULE_COMBO = 8;



    /**
     *
     *
     *
     *
     * Project types
     *
     */



    const PROJECT_ALL = 1;
    const PROJECT_FIND = 2;
    const PROJECT_DELETE = 3;
    const PROJECT_ADD = 4;
    const PROJECT_EDIT = 5;
    const PROJECT_COMBO = 6;


    /**
     *
     *
     *
     * Comment types
     *
     *
     */


    const COMMENT_FIND = 2;
    const COMMENT_ADD = 4;
    const COMMENT_EDIT = 5;



    /**
     *
     *
     *
     * Timeline info types
     *
     *
     */


    const TIMELINE_FIND = 2;
    const TIMELINE_ADD = 4;
    const TIMELINE_EDIT = 5;
}