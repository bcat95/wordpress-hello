<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Messages extends Action{
	public function __construct(){
		parent::__construct('messages'); 
	}

    public function getByThread(?string $thread_id) {
        return $this->query("SELECT * FROM $this->table WHERE id_thread='$thread_id'");
    }

    public function getPromptByIdUser(?int $id_customer) {
        return $this->query("SELECT m.id_prompt, p.name, p.status, p.expert, p.slug, p.image, MAX(m.created_at) as first_created_at, COUNT(DISTINCT m.id_thread) as num_unique_threads, COUNT(m.content) as num_messages FROM (SELECT id_prompt, id_thread, created_at, content FROM messages WHERE id_customer = " . $id_customer . ") AS m JOIN prompts AS p ON m.id_prompt = p.id WHERE p.status = 1 GROUP BY m.id_prompt, p.name, p.expert, p.slug ORDER BY first_created_at DESC");
    }

    public function getThreadByIdUserAndPrompt(?int $id_customer,?int $id_prompt) {
        return $this->query("SELECT m.id_thread, m.created_at, p.name, p.slug, latest_messages.content as last_message_content FROM `messages` AS m JOIN `prompts` AS p ON m.id_prompt = p.id JOIN ( SELECT id_thread, MAX(id) as max_id FROM `messages` WHERE id_customer = $id_customer AND id_prompt = $id_prompt GROUP BY id_thread ) AS latest_thread ON m.id_thread = latest_thread.id_thread LEFT JOIN `messages` AS latest_messages ON m.id_thread = latest_messages.id_thread AND latest_thread.max_id = latest_messages.id WHERE m.id_customer = $id_customer AND m.id_prompt = $id_prompt GROUP BY m.id_thread, p.name, p.slug ORDER BY m.created_at DESC");
    }

}