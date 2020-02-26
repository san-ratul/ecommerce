@extends('layouts.seller.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    You are Seller!
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente dolore dolor laborum fugiat quos. Laboriosam voluptas praesentium quis velit corrupti nostrum natus veritatis magni eos, ratione iure aliquid excepturi expedita quos tenetur molestiae sed neque vitae numquam maiores! Corporis in architecto neque placeat voluptas sunt sint ab recusandae iusto, assumenda unde velit quos totam obcaecati cupiditate asperiores tempora beatae sit perspiciatis non illo error eaque molestias. Eligendi eum ab inventore sapiente minima, neque vel ad veritatis adipisci in animi alias perspiciatis ratione commodi sequi placeat amet nobis? Cupiditate non accusamus dolores quia tenetur magnam debitis, repudiandae, dolor dicta quos ab.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
