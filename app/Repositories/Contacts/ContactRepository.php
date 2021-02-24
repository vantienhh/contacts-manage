<?php

namespace App\Repositories\Contacts;

use App\Models\Contacts\Contact;
use App\Repositories\BaseRepository;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    /**
     * @var Contact
     */
    protected $model;

    public function __construct(Contact $contact)
    {
        $this->setModel($contact);
    }

    public function incrementSubscribersCount($id, $count): void {
        $record = $this->getById($id);

        $record->increment('subscribers_count', $count);
    }

}
