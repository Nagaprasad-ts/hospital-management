<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('patient_name')
                    ->default(Auth::user()->name) // Show logged-in user's name
                    ->disabled(), // Make it non-editable

                Hidden::make('patient_id')
                    ->default(Auth::id()) // Store the logged-in user's ID
                    ->required(),
        Select::make('doctor_id')
                    ->relationship('doctor', 'name', function ($query) {
                        return $query->whereHas('roles', function ($q) {
                            $q->where('name', 'doctor');
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date')->required(),
                TimePicker::make('time')->required(),
                Select::make('status')
                    ->options([
                        'booked' => 'Booked',
                        'rescheduled' => 'Rescheduled',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('booked')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.name')->label('Patient')->sortable()->searchable(),
                TextColumn::make('doctor.name')->label('Doctor')->sortable()->searchable(),
                TextColumn::make('date')->sortable(),
                TextColumn::make('time')->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // If patient logs in, only show their own appointments
        if (Auth::user()->hasRole('patient')) {
            return $query->where('patient_id', Auth::id());
        }

        return $query;
    }
}
