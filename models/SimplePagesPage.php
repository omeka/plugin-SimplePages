<?php
require_once 'SimplePagesPageTable.php';

class SimplePagesPage extends Omeka_Record
{
    public $created_by_user_id;
    public $published = 0;
    public $title = null;
    public $slug;
    public $text = null;
    public $updated;
    public $inserted;
}