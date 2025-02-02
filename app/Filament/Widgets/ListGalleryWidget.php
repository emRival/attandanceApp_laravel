<?php

namespace App\Filament\Widgets;

use App\Models\GalleryRecognation;
use App\Services\FaceDescriptorService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                Tables\Columns\TextColumn::make('created_at')->label('Uploaded At')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->actions([
                // Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Manual Capture Image')
                        ->icon('heroicon-o-plus-circle')
                        ->url(route('faces.index', [
                            'role' => $role,
                            'id' => $this->record->id,
                        ])),
                    Tables\Actions\Action::make('Multiple Upload Image')
                        ->form([
                            FileUpload::make('image')
                                ->image()
                                ->imageEditor()
                                ->multiple() // Mengaktifkan multiple upload
                                ->panelLayout('grid')
                                ->directory(
                                    fn() => strtolower($this->record->position) === 'student'
                                        ? 'students_face'
                                        : 'teachers_face'
                                )
                                ->getUploadedFileNameForStorageUsing(
                                    fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                        ->prepend(strtolower($this->record->position) . 's_' . $this->record->id . '_' . now()->format('Ymd_His') . '_face-')
                                        ->append('.' . $file->getClientOriginalExtension())
                                ),
                            // Simpan ke folder berdasarkan role
                        ])
                        ->icon('heroicon-o-plus-circle')
                        ->action(function (array $data) {
                            $role = strtolower($this->record->position);
                            $studentId = $role === 'student' ? $this->record->id : null;
                            $teacherId = $role === 'teacher' ? $this->record->id : null;

                            // Looping untuk menyimpan setiap gambar
                            foreach ($data['image'] as $image) {
                                GalleryRecognation::create([
                                    'student_id' => $studentId,
                                    'teacher_id' => $teacherId,
                                    'image' => $image, // Simpan satu gambar per iterasi
                                ]);
                            }

                            // Kirim notifikasi sukses
                            Notification::make()
                                ->title('Upload Berhasil')
                                ->body('Semua gambar telah diunggah dan disimpan.')
                                ->success()
                                ->send();
                        }),

                ])
                    ->label('Input Image')
                    ->icon('heroicon-o-arrow-up-on-square-stack')
                    ->color('primary')
                    ->button(),
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
