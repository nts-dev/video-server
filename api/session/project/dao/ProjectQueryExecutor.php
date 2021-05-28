<?php

namespace session\project\dao;

use ReplicationDatabase;

class ProjectQueryExecutor
{
    private $conn;

    function __construct()
    {
        $this->conn = ReplicationDatabase::getInstance()::getConnection();
    }

    public function __destruct()
    {
        $this->conn = NULL;
    }

    function findAll()
    {


        $query = /** @lang text */
            "
                SELECT
                    projects_dir.id,
                    projects_dir.parent_id,

            IF(
                    ISNULL(
                            projects_dir_translation.title
                    ),
                    projects_dir.project_name,
                    projects_dir_translation.title
            )title,
             sort_id
            FROM
                    nts_site.projects_dir
            LEFT JOIN nts_site.projects_dir_translation ON projects_dir_translation.project_id = projects_dir.id
            AND projects_dir_translation.language_id =1 AND projects_dir.has_training = 1


            JOIN nts_site.project_to_branch ON project_to_branch.project_id = projects_dir.id AND project_to_branch.branch_id =1 ";

        $query .= " WHERE projects_dir.has_training = 1 AND archive = 0  ";

        $query .= " ORDER BY parent_id = 0 DESC,project_name asc";


        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }
}