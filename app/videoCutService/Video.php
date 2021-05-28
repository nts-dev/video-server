<?php


class Video
{
    private $conn;
    private $file_id_ = -1;
    private $file_name_;
    private $file_start_time_;
    private $file_end_time_;
    private $file_url_;
    private $file_ext_;
    private $file_map_;
    private $file_sort_;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function set_id($id)
    {
        if ($id)
            $this->file_id_ = $id;
    }

    public function get_id()
    {
        return $this->file_id_;
    }

    public function get_url()
    {
        return $this->file_url_;
    }

    public function set_url($string)
    {
        $this->file_url_ = $string;
    }

    public function set_map($project)
    {
        $this->file_map_ = $project;
    }

    public function set_sort($sort)
    {
        $this->file_sort_ = $sort;
    }

    public function set_ext($string)
    {
        $this->file_ext_ = $string;
    }

    public function set_name($string)
    {
        $this->file_name_ = $string;
    }

    public function get_name()
    {
        return $this->file_name_;
    }

    public function set_start($int)
    {
        $this->file_start_time_ = $int;
    }

    public function set_end($int)
    {
        $this->file_end_time_ = $int;
    }

    public function db_put_metadata()
    {
        $time = $this->getTimestamp();

        $query = " BEGIN;

            INSERT  INTO trs_metadata (start_time, end_time, link, `update`, project_id) VALUES(?,?,?,?,?);
            
            SET @file = CONCAT('".$this->file_name_."_', LAST_INSERT_ID());
            
            SET @link = CONCAT('".$this->file_url_."/', @file, '.".$this->file_ext_."');
            
            UPDATE trs_metadata SET  file_name = @file, link = @link WHERE id = LAST_INSERT_ID();
            
            COMMIT; ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $this->file_start_time_);
        $statement->bindParam(2, $this->file_end_time_);
        $statement->bindParam(3, $this->file_url_);
        $statement->bindParam(4, $time);
	    $statement->bindParam(5, $this->file_map_);
        return $this->returnStatement($statement);
    }

    public function db_get_sort($project, $parent)
    {
        $query = " SELECT sort_id FROM trs_metadata WHERE project_id = ? AND parent_id = ? ORDER BY id DESC LIMIT 1; ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $project);
        $statement->bindParam(2, $parent);
        return $this->returnStatement($statement);

    }


    public function db_get_children($id)
    {
        $query = " SELECT file_name FROM trs_metadata WHERE parent_id = ? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);

    }

    public function db_put_metadata_parent($parent)
    {
        $time = $this->getTimestamp();
        $is_new = 1;

        $query = " BEGIN;

            INSERT  INTO trs_metadata (start_time, end_time, link, `update`, project_id, file_name, sort_id, parent_id, is_new) VALUES(?,?,?,?,?,?,?,?,?);
                  
            COMMIT; ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $this->file_start_time_);
        $statement->bindParam(2, $this->file_end_time_);
        $statement->bindParam(3, $this->file_url_);
        $statement->bindParam(4, $time);
        $statement->bindParam(5, $this->file_map_);
        $statement->bindParam(6, $this->file_name_);
        $statement->bindParam(7, $this->file_sort_);
        $statement->bindParam(8, $parent);
        $statement->bindParam(9, $is_new);
        return $this->returnStatement($statement);

    }

    public function db_get_last_insert(){
        $query = "SELECT file_name FROM trs_metadata ORDER BY id DESC LIMIT 1";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    public function db_get_metadata($project){
        $query = "SELECT id, file_name, parent_id, sort_id, start_time, end_time, link, `update` FROM trs_metadata WHERE project_id = ? ORDER BY id desc ";
        $statement = $this->conn->prepare($query);
	$statement->bindParam(1, $project);
        return $this->returnStatement($statement);
    }

    public function db_get_metadata_parent_child($project){
        $query = " 
                     SELECT
                        P.id parent_id,
                        P.parent_id parent_parent_id,
                        (SELECT COUNT(*) FROM trs_metadata WHERE parent_id = P.id) children_count,
                        P.file_name parent_name,
                        C.parent_id child_parent_id,
                        P.sort_id parent_sort,
                        C.sort_id child_sort,
                        C.id child_id,
                        P.link parent_link,
                        C.file_name child_name,
                        C.start_time child_start,
                        C.end_time child_end,
                        C.link child_link,
                        C.`update` child_updated,
                        P.`update` parent_updated
                    FROM
                        trs_metadata P
                    LEFT JOIN trs_metadata C ON(P.id = C.parent_id)
                    WHERE P.project_id = ? AND P.is_new = 1 ORDER BY P.parent_id, P.id DESC
            ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $project);
        return $this->returnStatement($statement);
    }


    public function db_delete_record($id){
	$query = "DELETE FROM trs_metadata WHERE id = ?";
        $statement = $this->conn->prepare($query);
	$statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }


    public function db_delete_all($ids_todelete){
        $ids = implode (", ", $ids_todelete);
        $query = "DELETE FROM trs_metadata WHERE id in ($ids)";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    public function getTimestamp()
    {
        $timezone = 'Europe/Amsterdam';
        $timestamp = time();
        $date = new DateTime("now", new DateTimeZone($timezone)); //first argument "must" be a string
        $date->setTimestamp($timestamp); //adjust the object to correct timestamp
        return $date->format('Y-m-d H:i');
    }

    public function returnStatement($statement)
    {
        if ($statement->execute()) {
            return $statement;
        } else {
//            print_r($this->conn->errorInfo());
            return false;
        }
    }
}
