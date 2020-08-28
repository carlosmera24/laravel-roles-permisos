@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Productos</div>

                <div class="panel-body">

                    @can('products.edit')
                        <p class="text-right">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                Crear
                            </a>
                        </p>
                    @endcan

                    <table class="table table-striped">
                        @foreach($products as $product)
                        <tbody>
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>

                                @can('products.edit')
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-default">
                                        Editar
                                    </a>
                                </td>
                                @endcan

                                @can('products.show')
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-default">
                                        Ver
                                    </a>
                                </td>
                                @endcan

                                @can('products.destroy')
                                <td>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                                @endcan

                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
