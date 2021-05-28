<?php


class ProjectJSON implements ProjectResource
{

    private $projectsArray;

    public function __construct($resultArray = array())
    {
        $this->projectsArray = $resultArray;
    }


    function out()
    {
        header("Content-Type: application/json; charset=UTF-8");

        $projectObj = new stdClass;
        $projectObj->data = array();
        foreach ($this->projectsArray as $project_item) {
            $projects = new stdClass;

            $projects->id = (int) $project_item["id"];
            $projects->subject_title = $project_item["title"];
            $projects->subject_description = ProjectUtil::generateProjectId( $project_item["id"]);

            $projectObj->data[] = $projects;

        }
        echo json_encode($projectObj);
    }
}