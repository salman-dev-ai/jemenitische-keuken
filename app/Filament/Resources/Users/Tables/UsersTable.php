<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('roles.name')
                    ->label('الدور')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            'content_manager' => 'Content Manager',
                            default => $state ?? 'بدون دور',
                        }
                    ),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('role')
                    ->label('الدور')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin',
                        'content_manager' => 'Content Manager',
                    ])
                    ->query(function ($query, array $data) {
                        if (filled($data['value'] ?? null)) {
                            $query->whereHas(
                                'roles',
                                fn ($roleQuery) =>
                                    $roleQuery->where('name', $data['value'])
                            );
                        }
                    }),

            ])

            ->recordActions([
                EditAction::make()
                    ->label('تعديل'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
