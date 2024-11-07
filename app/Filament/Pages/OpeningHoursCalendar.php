<?php

    namespace App\Filament\Pages;

    use App\Filament\Widgets\CalendarWidget;
    use App\Models\OpeningHour;
    use Filament\Actions\Action;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Pages\Page;
    use Livewire\WithPagination;

    class OpeningHoursCalendar extends Page
    {
        use WithPagination;

        protected static ?string $navigationIcon = 'heroicon-o-document-text';
        protected static string $view = 'filament.pages.opening-hours-calendar';

        protected function getHeaderWidgets(): array
        {
            return [
                CalendarWidget::class,
            ];
        }

        protected function getActions(): array
        {
            return [
                Action::make('create')
                    ->label('Add Opening Hours')
                    ->form([
                        // Select Day
                        Select::make('selected_day')
                            ->label('Select a Day')
                            ->options([
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset opening/closing time when a day is selected
                                $set('open_time', '');
                                $set('close_time', '');
                            })
                            ->required(),

                        // Opening Time
                        TextInput::make('open_time')
                            ->label('Opening Time (HH:MM)')
                            ->required()
                            ->maxLength(5)
                            ->placeholder('HH:MM'),

                        // Closing Time
                        TextInput::make('close_time')
                            ->label('Closing Time (HH:MM)')
                            ->required()
                            ->maxLength(5)
                            ->placeholder('HH:MM'),
                    ])
                    ->action(function (array $data) {
                        $restaurantId = auth()->user()->restaurant_id;
                        // Save to the database
                        OpeningHour::create([
                            'restaurant_id' => $restaurantId,
                            'day_of_week' => $data['selected_day'],
                            'open_time' => $data['open_time'],
                            'close_time' => $data['close_time'],
                        ]);
                    }),
            ];
        }
    }
