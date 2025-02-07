<!-- resources/views/tables/columns/costumcolumn-tabel.blade.php -->

@if ($column->getName() === 'nama_siswa')
    <!-- Display the student's name in the first column -->
    <div class="text-left">
        {{ $getState() }}
    </div>
@elseif(str_contains($column->getName(), '_label'))
    <!-- Display the month label in bold and centered, only once -->
    <div class="text-center font-bold">
        {{ $getState() }}
    </div>
@elseif(str_contains($column->getName(), '_H') ||
        str_contains($column->getName(), '_S') ||
        str_contains($column->getName(), '_A'))
    <!-- Display the attendance status (H, S, A) values for each month -->
    <div class="text-center">
        {{ $getState() }}
    </div>
@endif
