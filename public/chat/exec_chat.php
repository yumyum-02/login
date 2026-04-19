<?php

/**
 * 掲示板一覧ページ用：投稿一覧を取得し $posts に格納する。
 * public/admin/chat.php からログイン確認後に require すること。
 *
 * @var list<array{id:int,message:string,created_at:string,created_by:int,author_name:string}> $posts
 */
$posts = getPostsForChatList();
