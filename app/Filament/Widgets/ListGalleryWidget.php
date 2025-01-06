<?php

namespace App\Filament\Widgets;

use App\Models\GalleryRecognation;
use App\Services\FaceDescriptorService;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ListGalleryWidget extends BaseWidget
{
    public $record; // Record akan diterima langsung dari halaman edit
    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Face Dataset';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        // Tentukan role berdasarkan position
        $role = strtolower($this->record->position); // Misalnya, "teacher" atau "student"

        return $table
            ->query(
                GalleryRecognation::query()
                    ->when($role === 'teacher', function ($query) {
                        $query->where('teacher_id', $this->record->id);
                    })
                    ->when($role === 'student', function ($query) {
                        $query->where('student_id', $this->record->id);
                    })
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')->square(),
                Tables\Columns\TextColumn::make('created_at')->label('Uploaded At'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Upload Image')
                    ->icon('heroicon-o-plus-circle')
                    ->url(route('faces.index', [
                        'role' => $role,
                        'id' => $this->record->id,
                    ])),
                Tables\Actions\Action::make('Generate Face Descriptor')
                    ->requiresConfirmation()
                    ->action(fn() => $this->generateFaceDescriptor()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function generateFaceDescriptor(): void
    {
        $service = app(FaceDescriptorService::class);

        try {
            $service->generateDescriptorForUser($this->record->id, $this->record->position);

            Notification::make()
                ->title('Success')
                ->body('Face descriptors generated successfully!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to generate face descriptors: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
