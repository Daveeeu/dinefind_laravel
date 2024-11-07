<?php

    namespace App\Filament\Widgets;

    use App\Models\OpeningHour;
    use App\Models\SpecialOpeningHour;
    use Carbon\Carbon;
    use Filament\Actions\Action;
    use Filament\Actions\CreateAction;
    use Filament\Actions\ViewAction;
    use Filament\Forms\Components\Checkbox;
    use Filament\Forms\Components\DateTimePicker;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\TextInput;
    use Saade\FilamentFullCalendar\Actions\DeleteAction;
    use Saade\FilamentFullCalendar\Actions\EditAction;
    use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

    class CalendarWidget extends FullCalendarWidget
    {
        protected $listeners = ['refreshCalendar' => 'fetchEvents'];

        public function fetchEvents(array $fetchInfo): array
        {
            $restaurantId = auth()->user()->restaurant_id;
            $regularHours = OpeningHour::where('restaurant_id', $restaurantId)->get();
            $specialHours = SpecialOpeningHour::where('restaurant_id', $restaurantId)->get();
            $currentDate = Carbon::now();
            $daysInMonth = $currentDate->daysInMonth;
            $events = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($currentDate->year, $currentDate->month, $day);
                $dayOfWeek = $date->format('l');

                $regularOpening = $this->getRegularOpening($dayOfWeek, $regularHours);
                $specialOpening = $this->getSpecialOpening($date, $specialHours);

                $events[] = $this->createEvent($date, $regularOpening, $specialOpening);
            }

            return array_filter($events);
        }

        private function getRegularOpening(string $dayOfWeek, $regularHours)
        {
            return $regularHours->firstWhere('day_of_week', $dayOfWeek);
        }

        private function getSpecialOpening(Carbon $date, $specialHours)
        {
            return $specialHours->firstWhere('date', $date->toDateString());
        }

        private function createEvent(Carbon $date, $regularOpening, $specialOpening)
        {
            if ($specialOpening) {
                return [
                    'title' => $specialOpening->is_closed ? 'Zárva' : "{$specialOpening->open_time} - {$specialOpening->close_time}",
                    'start' => $date->toDateString(),
                    'allDay' => true,
                    'color' => $specialOpening->is_closed ? '#FF0000' : '#00FF00',
                ];
            }

            if ($regularOpening) {
                return [
                    'title' => "{$regularOpening->open_time} - {$regularOpening->close_time}",
                    'start' => $date->toDateString(),
                    'allDay' => true,
                    'color' => '#0000FF',
                ];
            }

            return null;
        }

        public function config(): array
        {
            return [
                'firstDay' => 1,
                'headerToolbar' => [
                    'left' => 'dayGridWeek,dayGridDay',
                    'center' => 'title',
                    'right' => 'prev,next',
                ],
            ];
        }

        protected function headerActions(): array
        {
            return [
                CreateAction::make()
                    ->label('Add Opening Hours')
                    ->modalHeading('Add Opening Hours')
                    ->form([
                        TextInput::make('date')
                            ->label('Date')
                            ->default(fn () => now()->toDateString()),
                        Grid::make()
                            ->schema([
                                TextInput::make('open_time')
                                    ->label('Open Time'),
                                TextInput::make('close_time')
                                    ->label('Close Time'),
                            ]),
                            Checkbox::make('is_closed')
                                   ->label('Zárva'),
                    ])
                    ->action(function (array $data) {
                        // Save the opening hours based on the selected date and times
                        SpecialOpeningHour::create([
                            'date' => $data['date'],
                            'open_time' => $data['open_time'],
                            'close_time' => $data['close_time'],
                            'is_closed' => $data['is_closed'],
                            'restaurant_id' => auth()->user()->restaurant_id,
                        ]);
                    })
                    ->after(function () {
                        // Redirect the user to the same page, which will effectively reload the page
                        return redirect()->route('filament.admin.pages.opening-hours-calendar');
                    }),
            ];
        }

        protected function modalActions(): array
        {
            return [
                EditAction::make(),
                DeleteAction::make(),
            ];
        }

        protected function viewAction(): Action
        {
            return ViewAction::make();
        }

        public function getFormSchema(): array
        {
            return [
                TextInput::make('name'),
                Grid::make()->schema([
                    DateTimePicker::make('starts_at'),
                    DateTimePicker::make('ends_at'),
                ]),
            ];
        }
    }
