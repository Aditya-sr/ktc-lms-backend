<?php

namespace App\Livewire\Admin;

use App\Models\Admin\StudentIdCard;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IdCard extends Component
{
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $cardId;
    public $cardNumber;
    public $expiryDate;
    public $status = 'active';
    public $studentSearch = '';
    public $studentId;
    public $availableStudents = [];
    // Add new card
    public function addCard()
    {
        $this->resetForm();
        $this->showEditModal = true;
    }
    // Edit card
    public function editCard($id)
    {
        $card = StudentIdCard::find($id);
        if ($card) {
            $this->cardId = $card->id;
            $this->cardNumber = $card->card_number;
            $this->expiryDate = $card->expiry_date->format('Y-m-d');
            $this->status = $card->status;
            $this->studentId = $card->student_detail_id;
            $this->showEditModal = true;
        }
    }
    public function saveCard()
    {
        $validated = $this->validate([
            'cardNumber' => 'required|string|max:50',
            'expiryDate' => 'required|date',
            'status' => 'required|in:active,inactive',
            'studentId' => 'required|exists:student_details,id'
        ]);

        $data = [
            'card_number' => $this->cardNumber,
            'expiry_date' => $this->expiryDate,
            'status' => $this->status,
            'student_detail_id' => $this->studentId,
            'organization_id' => Auth::user()->organization_id,
            'issue_date' => now(),
        ];

        if ($this->cardId) {
            StudentIdCard::find($this->cardId)->update($data);
            $message = 'ID Card updated successfully!';
        } else {
            StudentIdCard::create($data);
            $message = 'ID Card created successfully!';
        }

        $this->showEditModal = false;
        $this->dispatch('toast', message: $message);
    }

    public function confirmDelete($id)
    {
        $this->cardId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCard()
    {
        StudentIdCard::find($this->cardId)->delete();
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'ID Card deleted successfully!');
    }

    public function generatePdf()
    {
        if (count($this->getSelected()) > 0) {
            $cards = StudentIdCard::with(['student', 'organization'])
                ->whereIn('id', $this->getSelected())
                ->get();

            $pdf = Pdf::loadView('admin.id-cards.bulk-pdf', [
                'cards' => $cards,
                'organization' => Auth::user()->organization
            ]);

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'id-cards-' . now()->format('Y-m-d') . '.pdf'
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.id-card');
    }
}
