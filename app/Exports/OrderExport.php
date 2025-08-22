<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['orderItems.product', 'user', 'table'])
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Pelanggan',
            'Meja',
            'Total (Rp)',
            'Status'
        ];
    }

    /**
     * @param Order $order
     * @return array
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->created_at->format('d-m-Y H:i'),
            $order->user ? $order->user->name : 'Tidak diketahui',
            $order->table ? $order->table->number : 'Tidak diketahui',
            $order->total_price,
            $order->status
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
