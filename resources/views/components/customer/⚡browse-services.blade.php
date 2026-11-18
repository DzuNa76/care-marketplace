
<?php

use App\Models\ProviderService;
use App\Models\ServiceCategory;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $category = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        $categories = ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $services = ProviderService::query()
            ->with([
                'category',
                'providerProfile.user',
                'prices' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('price'),
            ])
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('providerProfile', function ($query) {
                $query->whereHas('user');
            })
            ->when($this->category !== '', function ($query) {
                $query->whereHas('category', function ($categoryQuery) {
                    $categoryQuery->where('slug', $this->category);
                });
            })
            ->when(trim($this->search) !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($serviceQuery) use ($search) {
                    $serviceQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('providerProfile', function ($profileQuery) use ($search) {
                            $profileQuery
                                ->where('display_name', 'like', "%{$search}%")
                                ->orWhereHas('user', function ($userQuery) use ($search) {
                                    $userQuery->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->latest()
            ->paginate(9);

        return [
            'categories' => $categories,
            'services' => $services,
        ];
    }
};
?>

<div>
    {{-- Tampilan katalog akan dibuat pada tahap berikutnya. --}}
</div>
