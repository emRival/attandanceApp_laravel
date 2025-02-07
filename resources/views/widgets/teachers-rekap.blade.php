<x-filament::widget>
    <div class="p-6 bg-white rounded-lg shadow ">
        <h3 class="text-xl font-bold mb-4">{{ static::$heading }}</h3>

        {{-- Render Form Filter --}}
        <form wire:submit.prevent>
            {{ $this->form }}
        </form>

        {{-- Tabel Absensi --}}
        <div class="overflow-x-auto mt-6 rounded-lg">
            <table class="border-collapse border border-gray-200 w-full text-center"
                style="border-spacing: 0; table-layout: fixed;">

                <thead>
                    <tr>
                        <th rowspan="2" class="border p-2 bg-gray-100 sticky left-0" style="width: 160px;">Nama Guru
                        </th>

                        @foreach ($months as $short => $full)
                            <th colspan="3" class="border p-2 bg-gray-100 text-center" style="width: 123px;">
                                {{ $full }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($months as $short => $full)
                            <th class="border text-center"
                                style="width: 30px; height: 30px; padding: 0; line-height: 30px; background-color: #d1fae5; color: #065f46;">
                                H</th>
                            <th class="border text-center"
                                style="width: 30px; height: 30px; padding: 0; line-height: 30px; background-color: #fef9c3; color: #92400e;">
                                S</th>
                            <th class="border text-center"
                                style="width: 30px; height: 30px; padding: 0; line-height: 30px; background-color: #fee2e2; color: #7f1d1d;">
                                A</th>
                        @endforeach

                    </tr>
                </thead>

                <tbody>
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="border bg-white sticky left-0" style="width: 160px; height: 30px; padding: 5px;">
                                {{ $student['name'] }}</td>
                            
                            @foreach ($months as $short => $full)
                                <td class="border text-center"
                                    style="width: 30px; height: 30px; padding: 0; line-height: 30px; background-color: #d7f8e79c; color: #065f46;">
                                    {{ $student['attendance'][$short]['H'] ?? 0 }}</td>
                                <td class="border text-center"
                                    style="width: 30px; height: 30px; padding: 0; line-height: 30px; background-color: #fcf8d09c; color: #92400e;">
                                    {{ $student['attendance'][$short]['S'] ?? 0 }}</td>
                                <td class="border text-center"
                                    style="width: 30px; height: 30px; padding: 0; line-height: 30px; background-color: #fce5e59f; color: #7f1d1d;">
                                    {{ $student['attendance'][$short]['A'] ?? 0 }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-8 pt-3 rounded-lg flex justify-end ">
            {{ $paginator->links() }}
        </div>

    </div>
</x-filament::widget>
