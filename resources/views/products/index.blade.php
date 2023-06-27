@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option value="" selected disabled>--Select A Variant--</option>
                        @foreach ($groupedVariants as $title => $variants)
                            <optgroup label="{{ $title }}">
                                @foreach ($variants as $variant)
                                    <option value="{{$variant->variant}}">{{ $variant->variant }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="200px">Title</th>
                        <th width="300px">Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                    @foreach ($products as $product)
                    <tr>
                        <td>@php echo $i; @endphp</td>
                        <td>{{ $product['title'] }} <br> Created at : {{ $product['created'] }}</td>
                        <td>{{ Illuminate\Support\Str::limit($product['description'], 100) }}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                @foreach ($product['variants'] as $variant)
                                <dt class="col-sm-3 pb-0">
                                    {{ $variant['product_variant_one'] }} / {{ $variant['product_variant_two'] }}
                                    @if (isset($variant['product_variant_three']))
                                        / {{ $variant['product_variant_three'] }}
                                    @endif
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($variant['price'],2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($variant['stock']) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} out of {{ $count }}</p>
                </div>
                <div class="col-md-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
