
@if(View::exists("contracts.product.{$contract->product->slug}.{$contract->program->slug}.{$type}"))
    @include("contracts.product.{$contract->product->slug}.{$contract->program->slug}.{$type}", ['contract'=>$contract])
@endif

