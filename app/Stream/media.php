<?php

/**
 * 
 */
class Media {

    public $module_name;
    public $module_id;
    public $project_id;
    public $module_decription;
    public $module_added_by;
    public $project_name;
    public $lastInsertMediaFile;

    function __construct($db) {
        $this->conn = $db;
    }

    function __destruct() {
        $this->conn = NULL;
    }

    public function returnStatement($statement) {
        if ($statement->execute()) {
            return $statement;
        } else {
            return false;
        }
    }

    public function setXMLHeader() {
        ob_clean();
        header("Content-type:text/xml");
        echo "<?xml version='1.0'?>";
    }

    function defaultDate() {
        $timestamp = time();
        $date = new DateTime("now", new DateTimeZone('Europe/Amsterdam')); //first argument "must" be a string
        $date->setTimestamp($timestamp); //adjust the object to correct timestamp
        return $date->format('Y-m-d H:i');
    }

    function login($userid, $password) {
        if ($userid == '39554') {
            $query = /** @lang text */
                "SELECT
                        contact_attendent,
                        contact_id,
                        1 branch_id

   
            FROM
                    nts_site.relation_contact C
            WHERE

                            contact_id =? 
                AND pass = '" . md5($password) . "' ";
        } else {
            $query = /** @lang text */
                "
             SELECT
                    contact_attendent,
                    contact_id,
                    branch_id,
            pass
            FROM
                    nts_site.relation_contact
            JOIN nts_site.trainees ON trainees.IntranetID = relation_contact.contact_id
            WHERE

                            contact_id =?

             AND pass = '" . md5($password) . "' ";
        }
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $userid);
        return $this->returnStatement($statement);
    }

    function getModules($id) {
        $query = /** @lang text */
            " 
            SELECT
	M.ID ModuleID,
	M.ModuleName,
	X.FileName,
	X.fileSize,
	X.Alias,
	X.ID mediaId
FROM
	st_module M
LEFT JOIN st_media X ON X.Module = M.ID
WHERE
	M.`Subject` =:id
            ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        return $this->returnStatement($statement);
    }

    function getMedia($id) {
        $query = /** @lang text */
            " 
            SELECT
                S.FileName,
                S.Alias
                FROM
                st_media  S
                WHERE S.ID =:id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        return $this->returnStatement($statement);
    }

    function NTSLanguages() {
        $query = /** @lang text */
            "SELECT
                x.languages_id,
                x.`name`,
                x.image
        FROM
                 nts_site.xoops_shop_languages x";
        $statement = $this->conn->prepare($query);

        return $this->returnStatement($statement);
    }

    function subFileTextsLanguage_INSERT($language, $media) {
        $query = /** @lang text */
            " INSERT INTO st_text_files (language_id, video_id) VALUES(?, ?) ON DUPLICATE KEY UPDATE language_id = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $language);
        $statement->bindParam(2, $media);
        $statement->bindParam(3, $language);
        return $this->returnStatement($statement);
    }

    function audioTextLanguage_INSERT($language, $media) {
        $query = /** @lang text */
            " INSERT INTO st_audio_language (languageID, videoID) VALUES(?, ?) ON DUPLICATE KEY UPDATE languageID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $language);
        $statement->bindParam(2, $media);
        $statement->bindParam(3, $language);
        return $this->returnStatement($statement);
    }

    function audioTextLanguage_GET($media) {
        $query = /** @lang text */
            "SELECT
                        L.ID,  X.`name`,
                        X.languages_id
                FROM  st_audio_language L
                LEFT JOIN nts_site.xoops_shop_languages X ON X.languages_id = L.languageID
                WHERE
                        L.videoID = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $media);
        return $this->returnStatement($statement);
    }

    function subFileTextsLanguage($id) {
        $query = /** @lang text */
            "SELECT
                            T.sub_title,
                            T.overlaying_text,
                            T.sub_id,
                            X.`name`,
                            T.language_id ID
                    FROM
                            st_text_files T
                    LEFT JOIN nts_site.xoops_shop_languages X ON X.languages_id = T.language_id
                    WHERE
                            T.video_id =:id ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        return $this->returnStatement($statement);
    }

    function subFileTextsLanguage_UPDATE($media, $lang, $field, $value) {
        $query = /** @lang text */
            "UPDATE st_text_files SET $field = ?  WHERE video_id = ? AND language_id =? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $value);
        $statement->bindParam(2, $media);
        $statement->bindParam(3, $lang);
        return $this->returnStatement($statement);
//        return$query;
    }

    function generateContentCount($id) {
        $query = /** @lang text */
            "SELECT COUNT(ID) from st_module WHERE `Subject`=:id ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);

        if ($statement->execute()) {
            return $statement->fetchColumn();
        }
    }

    function courses_PHONE($languageId, $branchId, $user) {
        $host = "83.98.243.187";
        $db = "nts_site";
        $username = "poweruser";
        $password = "iMfFIg7gAxCmstc76KyQ";

        if ($languageId > 0) {
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
            )project_name,
             sort_id
            FROM
                    nts_site.projects_dir
            LEFT JOIN nts_site.projects_dir_translation ON projects_dir_translation.project_id = projects_dir.id
            AND projects_dir_translation.language_id =:lang AND projects_dir.has_training = 1";

            if ($branchId > 0) {
                $query .= " JOIN nts_site.project_to_branch ON project_to_branch.project_id = projects_dir.id AND project_to_branch.branch_id =:br ";
            }
        } else {
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
            )project_name,
             sort_id
            FROM
                    nts_site.projects_dir
            LEFT JOIN nts_site.projects_dir_translation ON projects_dir_translation.project_id = projects_dir.id
            AND projects_dir_translation.language_id =:lang AND projects_dir.has_training = 1 ";

            if ($branchId > 0) {
                $query .= " JOIN nts_site.project_to_branch ON project_to_branch.project_id = projects_dir.id AND project_to_branch.branch_id =:br ";
            }
        }


        $query .= " WHERE projects_dir.has_training = 1 AND archive = 0  ";
        if (!$user) {
            $query .= " AND (projects_dir.id = '10402' OR projects_dir.id = '10400' OR projects_dir.id = '10424' OR  projects_dir.parent_id = 0)";
        }
        $query .= " ORDER BY projects_dir.id DESC";


        try {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $username, $password);
            $statement = $conn->prepare($query);
            $statement->bindParam(':br', $branchId);
            $statement->bindParam(':lang', $languageId);
            return $this->returnStatement($statement);
        } catch (PDOExeption $exception) {
            return "Conection Error: " . $exception->getMessage();
        }
    }

    function courses($languageId, $branchId) {
        $host = "83.98.243.187";
        //        $host = "83.98.243.187";
        $db = "nts_site";
        $username = "poweruser";
        $password = "iMfFIg7gAxCmstc76KyQ";

        if ($languageId > 0) {
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
            )project_name,
             sort_id
            FROM
                    nts_site.projects_dir
            LEFT JOIN nts_site.projects_dir_translation ON projects_dir_translation.project_id = projects_dir.id
            AND projects_dir_translation.language_id =:lang AND projects_dir.has_training = 1";

            if ($branchId > 0) {
                $query .= " JOIN nts_site.project_to_branch ON project_to_branch.project_id = projects_dir.id AND project_to_branch.branch_id =:br ";
            }
        } else {
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
            )project_name,
             sort_id
            FROM
                    nts_site.projects_dir
            LEFT JOIN nts_site.projects_dir_translation ON projects_dir_translation.project_id = projects_dir.id
            AND projects_dir_translation.language_id =:lang AND projects_dir.has_training = 1 ";

            if ($branchId > 0) {
                $query .= " JOIN nts_site.project_to_branch ON project_to_branch.project_id = projects_dir.id AND project_to_branch.branch_id =:br ";
            }
        }


        $query .= " WHERE projects_dir.has_training = 1 AND archive = 0  ";

        $query .= " ORDER BY parent_id = 0 DESC,project_name asc";


        try {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $username, $password);
            $statement = $conn->prepare($query);
            $statement->bindParam(':br', $branchId);
            $statement->bindParam(':lang', $languageId);
            return $this->returnStatement($statement);
        } catch (PDOExeption $exception) {
            return "Conection Error: " . $exception->getMessage();
        }
    }

    function course_ONE($id) {
        $host = "83.98.243.187";
        $db = "nts_site";
        $username = "poweruser";
        $password = "iMfFIg7gAxCmstc76KyQ";

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
                    )project_name,
                     projects_dir.sort_id
                    FROM
                            nts_site.projects_dir
                    LEFT JOIN nts_site.projects_dir_translation ON projects_dir_translation.project_id = projects_dir.id
                    AND projects_dir_translation.language_id = 1
                    AND projects_dir.has_training = 1
                    LEFT JOIN nts_site.projects_dir X ON X.id = projects_dir.parent_id
                    WHERE
                            projects_dir.has_training = 1
                    AND projects_dir.archive = 0
                    AND projects_dir.id =:id

                    ORDER BY
                            projects_dir.parent_id = 0 DESC,
                            projects_dir.project_name ASC";
        try {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $username, $password);
            $statement = $conn->prepare($query);
            $statement->bindParam(':id', $id);
            return $this->returnStatement($statement);
        } catch (PDOExeption $exception) {
            return "Conection Error: " . $exception->getMessage();
        }
    }

    function contents($id) {
        $query = /** @lang text */
            "
             SELECT
                    st_module.ID,
                    st_module.ModuleName,
                    st_module.Description,
                    st_module.`Subject`,
                    st_module.date_updated,
                    st_module.Sort
            FROM
                    st_module
            WHERE st_module.`Subject` =:id ORDER BY st_module.Sort";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        return $this->returnStatement($statement);
    }

    function content_all() {


        $query = /** @lang text */
            "
            SELECT
                        C.ID content_id,
                        C.ModuleName content_name,
                        C.Description content_description,
                        C.`Subject` project_id,
                        C.date_updated content_date_updated,
                        C.Sort content_sort,
                        M.ID media_id,
                        M.FileName media_name,
                        M.Alias media_alias,
                        M.comments description,
                        M.fileType media_type,
                        M.fileSize media_size,
                        DATE_FORMAT( M.uploadDate, '%Y %M %d') media_upload_date,     
                        M.author,
                        M.Sort media_sort
                FROM
                        st_module C
                 LEFT JOIN st_media M ON M.Module = C.ID WHERE C.`Subject` > 100 ORDER BY  M.ID DESC";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function course_ADD($parent, $name) {
        $host = "83.98.243.187";
        $db = "nts_site";
        $username = "poweruser";
        $password = "iMfFIg7gAxCmstc76KyQ";

        $query = "
            BEGIN;

            SET @userId = '15683,131,9392,9068,9562,16848';

            INSERT INTO projects_dir(
                    `parent_id`,
                    `project_name`,
                    `sort_id`,
                    `proj_uID`,
                    `has_training`
            )SELECT
                    $parent,
                    '$name',

            IF(
                    (MAX(sort_id) > 0),
                    MAX(sort_id)+ 1,
                    1
            )sort_id,
             @userId,
            1
            FROM
                    projects_dir
            WHERE
                    parent_id =:parent;

            SET @last_insert = LAST_INSERT_ID();

            INSERT IGNORE INTO project_to_branch (branch_id,project_id) SELECT Branch_ID, @last_insert FROM branch WHERE visible_in_projects = 1 ORDER BY Branch_ID;

            COMMIT; ";

        try {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $username, $password);
            $statement = $conn->prepare($query);
            $statement->bindParam(':parent', $parent);
            return $this->returnStatement($statement);
        } catch (PDOExeption $exception) {
            return "Conection Error: " . $exception->getMessage();
        }
    }

    function course_DELETE($id) {
        $host = "83.98.243.187";
        $db = "nts_site";
        $username = "poweruser";
        $password = "iMfFIg7gAxCmstc76KyQ";

        $query = /** @lang text */
            "DELETE FROM projects_dir WHERE id =:id ";

        try {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $username, $password);
            $statement = $conn->prepare($query);
            $statement->bindParam(':id', $id);
            return $this->returnStatement($statement);
        } catch (PDOExeption $exception) {
            return "Conection Error: " . $exception->getMessage();
        }
    }

    function course_UPDATE($id, $name) {
        $host = "83.98.243.187";
        $db = "nts_site";
        $username = "poweruser";
        $password = "iMfFIg7gAxCmstc76KyQ";

        $query = /** @lang text */
            "UPDATE projects_dir SET project_name =? WHERE id=? ";

        try {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $username, $password);
            $statement = $conn->prepare($query);
            $statement->bindParam(1, $name);
            $statement->bindParam(2, $id);

            return $this->returnStatement($statement);
        } catch (PDOExeption $exception) {
            return "Conection Error: " . $exception->getMessage();
        }
    }

    function content_ADD($id) {
        $date = $this->defaultDate();
        $query = /** @lang text */
            "INSERT INTO st_module (Sort, Subject, date_added, date_updated) 
                    SELECT CASE WHEN Sort > 0 THEN (MAX(Sort)+1) ELSE 1 END sort, '$id' `Subject`, '$date', '$date'  FROM st_module WHERE `Subject` =? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function content_ADD_FROM_APP($id) {
        $date = $this->defaultDate();
        $query = /** @lang text */
            "INSERT INTO st_module (Sort, Subject, date_added, date_updated, ModuleName, Description) 
                    SELECT CASE WHEN Sort > 0 THEN (MAX(Sort)+1) ELSE 1 END sort, '$id' `Subject`, '$date', '$date', '$this->module_name', '$this->module_decription'  FROM st_module WHERE `Subject` =? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function content_DELETE($id) {
        $query = /** @lang text */
            "DELETE FROM st_module WHERE ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function content_ONE($id) {
        $query = /** @lang text */
            "SELECT * FROM st_module WHERE st_module.ID =:id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        return $this->returnStatement($statement);
    }

    function content_LAST_INSERT() {
        $query = /** @lang text */
            "SELECT * FROM st_module WHERE st_module.ID =LAST_INSERT_ID()";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function content_VIDTREE($id) {
        $query = /** @lang text */
            "SELECT * FROM st_module WHERE st_module.`Subject` =:id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        return $this->returnStatement($statement);
    }

    function content_UPDATE($id, $module, $description) {
        $date = $this->defaultDate();
        $query = /** @lang text */
            "UPDATE st_module SET ModuleName =?, Description=?, date_updated=? WHERE ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $module);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $date);
        $statement->bindParam(4, $id);
        return $this->returnStatement($statement);
    }

    function media_INSERT($content, $filename, $date, $file_size, $file_type, $sort, $client) {
        $hash = $this->encodeVideoId($date);
        $query = /** @lang text */
            "
            INSERT INTO st_media(
                    FileName,
                    Module,
                    fileType,
                    fileSize,
                    uploadDate,
                    Sort,
                    author,
                    hash
            )
            VALUE
                    ('$filename','$content','$file_type','$file_size','$date','$sort', '$client', '$hash')";
        $statement = $this->conn->prepare($query);
        if ($statement->execute()) {
            $this->lastInsertMediaFile = $this->conn->lastInsertId();
            return $statement;
        } else {
            return false;
        }
    }

    function media_updateAlias($extention, $action) {
        $extention = '.' . $extention;
        $query = /** @lang text */
            "UPDATE  st_media A
        JOIN st_media B ON B.ID = A.ID
        
        SET A.Alias = CONCAT('f_',B.ID,'$extention') ";
        if ($action === 'new')
            $query .= " WHERE A.ID = LAST_INSERT_ID()";
        else
            $query .= "      WHERE A.ID = B.ID ";

        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function media_REPLACE($content, $filename, $date, $alias, $file_size, $file_type, $id) {
        $query = /** @lang text */
            "
            UPDATE  st_media SET
                    FileName =?,
                    Alias = ?,
                    fileType = ?,
                    fileSize = ?,
                    uploadDate = ? WHERE ID =?";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $filename);
        $statement->bindParam(2, $alias);
        $statement->bindParam(3, $file_type);
        $statement->bindParam(4, $file_size);
        $statement->bindParam(5, $date);
        $statement->bindParam(6, $id);
        return $this->returnStatement($statement);
    }

    function media_LAST_SORT($module) {
        $query = /** @lang text */
            " SELECT MAX(Sort) Sort FROM st_media M WHERE M.Module =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $module);
        return $this->returnStatement($statement);
    }

    function media_files($id) {
        $query = /** @lang text */
            "
            SELECT
                    M.ID,
                    M.FileName,
                    M.Alias,
                    M.comments description,
                    M.Module,
                    M.fileType,
                    M.fileSize,
                    M.uploadDate,
                    M.Sort,
                   M.hash
            FROM
                    st_media M

            WHERE M.Module =? ORDER BY M.Sort";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function media_files_all() {
        $query = /** @lang text */
            "
            SELECT
                    M.ID,
                    M.FileName,
                    M.Alias,
                    M.comments description,
                    M.Module,
                    M.fileType,
                    M.fileSize,
                    M.uploadDate,
                    M.Sort,
                   M.hash
            FROM
                    st_media M";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function media_file($id) {
        $query = /** @lang text */
            "
            SELECT
                    M.ID,
                    M.FileName,
                    M.Alias,
                    M.Description,
                    M.Module,
                    M.fileType,
                    M.fileSize,
                    M.uploadDate,
                    M.comments
            FROM
                    st_media M

            WHERE M.ID =? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function media_file_ALL() {
        $query = /** @lang text */
            "
            SELECT
                    M.ID,
                    M.Alias
            FROM
                    st_media M ";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function media_files_DELETE($id) {
        $query = /** @lang text */
            "DELETE FROM st_media WHERE ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function media_files_FILENAME($id) {
        $query = /** @lang text */
            "SELECT Alias FROM st_media WHERE ID=? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        if ($statement->execute()) {
            return $statement->fetchColumn();
        } else {
            return false;
        }
    }

    function media_files_ALIES($id) {

        $query = /** @lang text */
            "SELECT Alias,FileName FROM st_media WHERE ID= ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function media_files_ALIES_LAST_INSERT() {

        $query = /** @lang text */
            "SELECT Alias FROM st_media WHERE ID= LAST_INSERT_ID()";
        $statement = $this->conn->prepare($query);
        if ($statement->execute()) {
            return $statement->fetchColumn();
        } else {
            return false;
        }
    }

    function FILE_ID_BY_ALIES($alias) {

        $query = /** @lang text */
            "SELECT ID FROM st_media WHERE Alias=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $alias);
        if ($statement->execute()) {
            return $statement->fetchColumn();
        } else {
            return false;
        }
    }

    function media_files_UPDATE($id, $desc, $name) {
        $query = /** @lang text */
            "UPDATE st_media SET FileName=?, comments =? WHERE ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $name);
        $statement->bindParam(2, $desc);
        $statement->bindParam(3, $id);
        return $this->returnStatement($statement);
    }

    function content_UPDATE_COMMENT($id, $text) {
        $query = /** @lang text */
            "UPDATE st_module SET `comment` =? WHERE ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $text);
        $statement->bindParam(2, $id);
        return $this->returnStatement($statement);
    }

    function time_caption($id) {
        $query = /** @lang text */
            "SELECT * FROM st_captionTime WHERE fileID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function time_caption_INSERT($id, $count) {
        if ($count > 0) {
            $query = /** @lang text */
                "INSERT INTO st_captionTime(`begin`, fileID)SELECT
                    E.`end`,
                '$id'
                FROM	st_captiontime E WHERE	E.fileID =? ORDER BY E.time_ID DESC LIMIT 1";
        } else {
            $query = /** @lang text */
                "INSERT INTO st_captionTime(`begin`, fileID) VALUES('00:00:00.100', ?) ";
        }
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function translations_UPDATE($id, $field, $value) {
        $query = /** @lang text */
            "UPDATE st_translations SET " . $field . " =? WHERE ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $value);
        $statement->bindParam(2, $id);
        return $this->returnStatement($statement);
    }

    function translations($id) {
        $query = /** @lang text */
            "SELECT L.*, x.`name` FROM st_translations L 
                LEFT JOIN nts_site.xoops_shop_languages x ON x.languages_id = L.`language`
                WHERE L.time_ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function translations_INSERT_DEFAULTS($id) {
        $query = /** @lang text */
            "INSERT INTO st_translations(time_ID, `language`)
            SELECT
                    '$id',
                    x.languages_id
            FROM
                    nts_site.xoops_shop_languages x
            WHERE
                    x.languages_id IN(1,4)";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function time_caption_UPDATE($id, $field, $value) {
        $query = /** @lang text */
            "UPDATE st_captionTime SET " . $field . " =? WHERE time_ID=?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $value);
        $statement->bindParam(2, $id);
        return $this->returnStatement($statement);
    }

    function caption_generator($id) {
        $query = /** @lang text */
            "
            SELECT
                T.time_ID,
                T.`begin`,
                T.`end`,
                T.fileID,
                C.`language`,
                C.translations,
                C.ID caption_Id,
                F.FileName,
                X.`name` language_name,
                P.font,
                P.font_size,
                P.background,
                P.color,
                P.text_position,
                P.ID propertiesID
        FROM
                st_captiontime T
        LEFT JOIN st_translations C ON C.time_ID = T.time_ID
        JOIN st_media F ON F.ID = T.fileID 
        LEFT JOIN nts_site.xoops_shop_languages X ON X.languages_id = C.`language`
        LEFT JOIN st_subtitle_properties P ON P.mediaID = T.fileID
        WHERE
                T.fileID =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function subtitle_procedures_UPDATE($text) {
        $query = /** @lang text */
            "INSERT INTO st_procedures(ID, `Procedure`)
                VALUES
                        (1, ?)ON DUPLICATE KEY UPDATE `Procedure` = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $text);
        $statement->bindParam(2, $text);
        return $this->returnStatement($statement);
    }

    function subtitle_procedures() {
        $query = /** @lang text */
            "SELECT * FROM st_procedures ";
        $statement = $this->conn->prepare($query);
        return $this->returnStatement($statement);
    }

    function ecryption_ACTIONS($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'mediaserver';
        $secret_iv = 'mediasecret';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function content_SORTATION($action, $id, $nextId) {
        switch ($action) {
            case 'up':
                $query = "
                    BEGIN;
                    
                    UPDATE st_module A,
                            (SELECT Sort FROM st_module WHERE ID =:id) B
                            SET A.Sort = B.Sort-1
                    WHERE A.ID =:id ; 
                    
                    UPDATE st_module A,
                                        (SELECT Sort FROM st_module WHERE ID =:next) B
                                        SET A.Sort = B.Sort+1
                    WHERE A.ID =:next ;
                    
                    COMMIT;";
                break;

            case 'down':
                $query = "
                    BEGIN;
                    
                    UPDATE st_module A,
                            (SELECT Sort FROM st_module WHERE ID =:id) B
                            SET A.Sort = B.Sort+1
                    WHERE A.ID =:id ; 
                    
                    UPDATE st_module A,
                                        (SELECT Sort FROM st_module WHERE ID =:next) B
                                        SET A.Sort = B.Sort-1
                    WHERE A.ID =:next ;
                    
                    COMMIT;";
                break;
        }

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':next', $nextId);
        return $this->returnStatement($statement);
    }

    function media_SORTATION($action, $id, $nextId) {
        switch ($action) {
            case 'up':
                $query = "
                    BEGIN;
                    
                    UPDATE st_media A,
                            (SELECT Sort FROM st_media WHERE ID =:id) B
                            SET A.Sort = B.Sort-1
                    WHERE A.ID =:id; 
                    
                    UPDATE st_media A,
                                        (SELECT Sort FROM st_media WHERE ID =:next) B
                                        SET A.Sort = B.Sort+1
                    WHERE A.ID =:next ;
                    
                    COMMIT;";
                break;

            case 'down':
                $query = "
                    BEGIN;
                    
                    UPDATE st_media A,
                            (SELECT Sort FROM st_media WHERE ID =:id) B
                            SET A.Sort = B.Sort+1
                    WHERE A.ID =:id ; 
                    
                    UPDATE st_media A,
                                        (SELECT Sort FROM st_media WHERE ID =:next) B
                                        SET A.Sort = B.Sort-1
                    WHERE A.ID =:next ;
                    
                    COMMIT;";
                break;
        }

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':next', $nextId);
        return $this->returnStatement($statement);
    }

    function subtitle_properties_INSERT($media, $font, $font_size, $color, $background, $text_position) {
        $query = /** @lang text */
            "INSERT INTO st_subtitle_properties (font, font_size,background, text_position, color, mediaID)
                        VALUES (?,?,?,?,?,?) 
                ON DUPLICATE KEY
                        UPDATE font=?, font_size=?,background=?,text_position=?,color=?";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $font);
        $statement->bindParam(2, $font_size);
        $statement->bindParam(3, $background);
        $statement->bindParam(4, $text_position);
        $statement->bindParam(5, $color);
        $statement->bindParam(6, $media);

        $statement->bindParam(7, $font);
        $statement->bindParam(8, $font_size);
        $statement->bindParam(9, $background);
        $statement->bindParam(10, $text_position);
        $statement->bindParam(11, $color);
        return $this->returnStatement($statement);
    }

    function subtitle_properties($media) {
        $query = /** @lang text */
            "SELECT * FROM st_subtitle_properties WHERE mediaID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $media);
        return $this->returnStatement($statement);
    }

    function audioFiles_GET($id) {
        $query = /** @lang text */
            "SELECT
                        st_audio_file.SortID,
                        st_audio_file.BeginTime,
                        st_audio_file.EndTime,
                        st_audio_file.Content,
                        st_audio_file.`Status`,
                        st_audio_file.FileAlias,
                        st_audio_file.Author,
                        st_audio_file.Updated,
                        st_audio_file.ID
                FROM
                        st_audio_file
                WHERE
                        st_audio_file.text_languageID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function audioFiles_PER_VID($id, $lang) {
        $query = /** @lang text */
            "SELECT
                        L.languageID,
                        L.videoID,
                        F.BeginTime,
                        F.EndTime,
                        F.FileAlias,
                        F.Updated,
                        F.Content,
                        F.ID,
                        F.SortID
                FROM
                        st_audio_language L
                LEFT JOIN st_audio_file F ON F.text_languageID = L.ID
                WHERE
                        L.videoID =?
                AND F.`Status` = 0 AND L.languageID =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindParam(2, $lang);
        return $this->returnStatement($statement);
    }

    function audioFiles_TIMESTAMP($id) {
        $query = /** @lang text */
            "
            SELECT
                    F.ID,
                    F.text_languageID,
                    F.SortID,
                    F.EndTime,
                    F.BeginTime,
                    F.Content,
                    (
                            SELECT
                                    P.EndTime
                            FROM
                                    st_audio_file P
                            WHERE
                                    P.ID =(
                                            SELECT
                                                    MAX(ID)
                                            FROM
                                                    st_audio_file
                                            WHERE
                                                    ID < F.ID
                                            AND text_languageID = F.text_languageID
                                    )
                    )previousEndTime
            FROM
                    st_audio_file F
            WHERE
                    F.text_languageID =?";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function audioFileDetail_INSERT($id, $begin) {
        $query = /** @lang text */
            "INSERT INTO st_audio_file(
                        text_languageID,
                        BeginTime,
                        Status,
                        SortID
                )SELECT
                        $id,
                        '$begin',
                            0,
                        CASE
                WHEN MAX(SortID)> 0 THEN
                        MAX(SortID)+ 1
                ELSE
                        1
                END sort
                FROM
                        st_audio_file
                WHERE
                        text_languageID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function audioFileDetail_UPDATE_TEXT($itemId, $fileName, $content) {
        $query = /** @lang text */
            "UPDATE st_audio_file SET Content = '$content', FileAlias= '$fileName' WHERE ID = $itemId";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $content);
        $statement->bindParam(2, $fileName);
        $statement->bindParam(3, $itemId);
        return $this->returnStatement($statement);
    }

    function audioFileDetail_UPDATE($itemId, $field, $value) {
        $time = $this->defaultDate();
        $query = /** @lang text */
            "UPDATE st_audio_file SET $field = ?, Updated = ? WHERE ID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $value);
        $statement->bindParam(2, $time);
        $statement->bindParam(3, $itemId);
        return $this->returnStatement($statement);
    }

    function audioFileDetail_UPDATE_FIELDS($itemId, $field, $value) {
        $query = /** @lang text */
            "UPDATE st_audio_file SET $field = ? WHERE ID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $value);
        $statement->bindParam(2, $itemId);
        return $this->returnStatement($statement);
    }

    function audioFileDetail_DELETE($itemId) {
        $query = /** @lang text */
            "DELETE FROM st_audio_file WHERE ID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $itemId);
        return $this->returnStatement($statement);
    }

    function audioFile_CONTENT($id) {
        $query = /** @lang text */
            "SELECT   st_audio_file.Content   FROM    st_audio_file WHERE st_audio_file.ID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        if ($statement->execute()) {
            return $statement->fetchColumn();
        }
    }

    function audioMovie_INSERT($lang, $name) {
        $time = $this->defaultDate();

        $query = /** @lang text */
            "INSERT INTO st_audiomovie (LanguageID, ItemName, LastUpdate) VALUES(?, ?, ?)
                ON DUPLICATE KEY UPDATE ItemName=?, LastUpdate =? ";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $lang);
        $statement->bindParam(2, $name);
        $statement->bindParam(3, $time);
        $statement->bindParam(4, $name);
        $statement->bindParam(5, $time);
        return $this->returnStatement($statement);
    }

    function audioMovie($itemId) {

        $query = /** @lang text */
            "SELECT ID, ItemName, LastUpdate FROM st_audiomovie WHERE LanguageID = ? ";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $itemId);
        return $this->returnStatement($statement);
    }

    function audioMovieByVideoID($lang) {
        $query = /** @lang text */
            "SELECT
                    L.ID,
                    L.videoID,
                    L.languageID,
                    X.`name`,
                    A.ItemName,
                    A.LastUpdate
            FROM
                    st_audio_language L
            JOIN nts_site.xoops_shop_languages X ON X.languages_id = L.languageID
            JOIN st_audiomovie A ON A.LanguageID = L.ID
            WHERE
                    L.videoID = ?";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $lang);
        return $this->returnStatement($statement);
    }

    function audioMovie_HLS_fun($Id) {
        $query = /** @lang text */
            "SELECT
                        M.ItemName,
                        X.`name` languageName,
                        L.videoID,
                        L.languageID
                FROM
                        st_audio_language L
                JOIN st_audiomovie M ON M.LanguageID = L.ID
                JOIN nts_site.xoops_shop_languages X ON X.languages_id = L.languageID
                WHERE
                        L.videoID = ?";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $Id);
        return $this->returnStatement($statement);
    }

    function audio_IMPORT_TIMELINE($columns, $items) {

        $insert = /** @lang text */
            "INSERT INTO st_audio_file (" . implode(",", $columns) . ") VALUES " . implode(",", $items);
        $statement = $this->conn->prepare($insert);
        return $this->returnStatement($statement);
    }

    function st_audio_languageGET_LAST_INSERT($videoId, $languageId) {
        $insert = $this->st_audio_language_LAST_INSERT($videoId, $languageId);
        if ($insert) {
            $query = /** @lang text */
                " SELECT ID FROM st_audio_language WHERE ID = LAST_INSERT_ID(); ";
            $statement = $this->conn->prepare($query);
            if ($statement->execute()) {
                return $statement->fetchColumn();
            } else {
                return false;
            }
        } else
            "Not inserted";
    }

    function st_audio_language_LAST_INSERT($videoId, $languageId) {
        $query = /** @lang text */
            " INSERT INTO st_audio_language (videoID, languageID)     VALUES(?, ?); ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $videoId);
        $statement->bindParam(2, $languageId);
        return $this->returnStatement($statement);
    }

    function getUserStat($userId) {
        $query = /** @lang text */
            "SELECT
                    S.UserId,
                    S.playback_position,
                    S.total_playback,
                    S.viewed_time,
                    S.host_device,
                    C.ID content_id,
                    C.ModuleName content_name,
                    C.Description content_description,
                    C.`Subject` project_id,
                    C.date_updated content_date_updated,
                    C.Sort content_sort,
                    M.ID media_id,
                    M.FileName media_name,
                    M.Alias media_alias,
                    M.comments description,
                    M.fileType media_type,
                    M.fileSize media_size,
                    M.uploadDate media_upload_date,
                    M.Sort media_sort
            FROM
                    st_module C
            JOIN st_media M ON M.Module = C.ID
            JOIN st_user_preview_stat S ON S.media_id = M.ID
            WHERE
                    S.UserId = ? 
                    ORDER BY S.viewed_time DESC";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $userId);
        return $this->returnStatement($statement);
    }

    function userStat_INSERT($user) {
        $query = /** @lang text */
            " INSERT INTO st_user_preview_stat (UserId,viewed_time,host_device,media_id,ip_address,playback_position, total_playback) VALUES(?,?,?,?,?,?,?)
                 ON DUPLICATE KEY UPDATE viewed_time =? , playback_position = ? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $user->stat_viewer);
        $statement->bindParam(2, $user->stat_date_time);
        $statement->bindParam(3, $user->stat_device_host);
        $statement->bindParam(4, $user->stat_media_id);
        $statement->bindParam(5, $user->stat_ip_address);
        $statement->bindParam(6, $user->stat_media_playback_position);
        $statement->bindParam(7, $user->stat_media_playback_total);
        $statement->bindParam(8, $user->stat_date_time);
        $statement->bindParam(9, $user->stat_media_playback_position);
        return $this->returnStatement($statement);
    }

    function audioTextLanguage_DELETE($id) {
        $query = /** @lang text */
            " DELETE FROM st_audio_language WHERE ID = ?; ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function filmScript_SAVE($id, $content) {
        $date = $this->defaultDate();
        $query = /** @lang text */
            "INSERT INTO st_scripts (media_id, content, created_at) VALUES(?,?,?) ON DUPLICATE KEY UPDATE    
        content =?, updated_at =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindParam(2, $content);
        $statement->bindParam(3, $date);
        $statement->bindParam(4, $content);
        $statement->bindParam(5, $date);
        return $this->returnStatement($statement);
    }


    function comments_SAVE($mediaId, $content) {
        $date = $this->defaultDate();
        $query = /** @lang text */
            "INSERT INTO media_comment (media_id, content, updated_at) VALUES(?,?,?) ON DUPLICATE KEY UPDATE    
        content =?, updated_at =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $mediaId);
        $statement->bindParam(2, $content);
        $statement->bindParam(3, $date);
        $statement->bindParam(4, $content);
        $statement->bindParam(5, $date);
        return $this->returnStatement($statement);
    }


    function mediaInfo_SAVE($mediaId, $content) {
        $date = $this->defaultDate();
        $query = /** @lang text */
            "INSERT INTO media_info (media_id, content, updated_at) VALUES(?,?,?) ON DUPLICATE KEY UPDATE    
        content =?, updated_at =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $mediaId);
        $statement->bindParam(2, $content);
        $statement->bindParam(3, $date);
        $statement->bindParam(4, $content);
        $statement->bindParam(5, $date);
        return $this->returnStatement($statement);
    }


    function mediaInfo_GET($id){
        $query = /** @lang text */
            "SELECT * FROM media_info WHERE media_id =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }


    function mediaComment_GET($id){
        $query = /** @lang text */
            "SELECT * FROM media_comment WHERE media_id =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function filmScript_GET($id){
        $query = /** @lang text */
            "SELECT * FROM st_scripts WHERE media_id =?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }

    function mediaResources_GET($id){
        $query =
            /** @lang text */
            "SELECT 
                    C.ID content_id,
                    M.fileType media_type,
                    M.ID media_id, 
                    C.`Subject` project_id ,
                    I.content info,
                    T.content comments
                    FROM st_module C 
                    JOIN st_media M ON M.Module = C.ID
                    LEFT JOIN media_info I ON I.media_id = M.ID
                    LEFT JOIN media_comment T ON T.media_id = M.ID
                     WHERE M.hash = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $id);
        return $this->returnStatement($statement);
    }


    public function encodeVideoId($id)
    {
        $seed = 'JvKnrQWPsThuJteNQAuH' . $id;
        return substr(hash('sha256', $seed), 0, 19);
    }

    public function updateVideosWithHashCodes($id){
        $hash = $this->encodeVideoId($id);
        $query = /** @lang text */
            " UPDATE st_media SET hash = ? WHERE ID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $hash);
        $statement->bindParam(2, $id);
        return $this->returnStatement($statement);
    }
}
