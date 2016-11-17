<?php
$postMessages = include_once __DIR__.'/../../../post/messages/th/andacms.php';
return array_replace_recursive($postMessages, [
    'Create Root' => 'สร้างรูท',
    'Create Child' => 'สร้างย่อย',
    'Please choose an a-z, 0-9 (sample: abc123)' => 'กรุณากรอก a-z, 0-9 (ตัวอย่าง: abc123)',
    'ID' => 'Id',
    'Root' => 'รูท',
    'Lft' => 'ซ้าย',
    'Rgt' => 'ขวา',
    'Level' => 'ระดับ',
    'Status' => 'สถานะ',
    'Name' => 'ชื่อ',
//    'Title',
//    'Slug',
//    'Image',
//    'Created By',
//    'Created At',
//    'Updated By',
//    'Updated At',
//    'Meta Title',
//    'Meta Keywords',
//    'Meta Description',
]);