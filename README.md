# Livewire Basics
# Video 1 (How it Works)

# To get started, let's review the basics of Livewire and build the obligatory counter example. Next, we'll take a brief look at the request/response lifecycle of a Livewire component.

# live wire works on ajax conecpet make changes in real time. js framework goes under build when you change something. make reactive components without javascript.

# Request Response Lifecycle of LiveWire

# live wire component are render on server that why yits seo friendly
# work on ajax
# after changed server render component and send new html
# and the live wire javascript update what changes in our html.

# we will work on these things in these series.
# forms
# search bar
# datatable componenets with seach bar, filter and pagination.
# comment with alert message.

# docs for livewire installation
https://laravel-livewire.com/

# or you can directly install with laravel

# everytime you want to make something with livewire you will make component because its component based.

# use this command for making live wire components
php artisan make:livewire counter

# It will make two files one is controller and one will be the view.
# The controller class is like a script in view and the view is simple wiew template in view.
# we can call wire:model or wire:click to call methods in controller.

# call livewire component in view file.
<div>
    <@livewire('counter')
</div>

# livewire controller
<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public function increment() {
        $this->count++;
    }

    public function decrement() {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}

# livewire view
<div>
    <span>{{ $count }}</span>
    <button wire:click="increment()">+</button>
    <button wire:click="decrement()">-</button>
</div>

# wire class directive on which we can call livewire methods
wire:click 
# like @click in view

wire:model
# like v-model in view

------------------------------------------------------------------------------------------------

# Video 2 (Contact Form With Validation)

# Let's convert a vanilla contact form to use Livewire. This will give us the opportunity to also review real-time validation. This component should give you a solid understanding of the basics of Livewire, including binding data with wire:model, calling methods on the server, and re-rendering views.

# important directories
# 1. wire:model use to put input filed value to server variables.
# 2. wire:submit.prevent use to submit form.
# 3. wire:click="$set('successMessage', null)" use $set to update value of some varaible.
# 4. wire:click use to call some function.
# 5. wire:loading use when there is response waiting from the server then your code will run.
# 6. wire:target="submitForm" use when the response waiting from the submitForm function in the controller then our code will work.

# when we write something and stop it directly called the ajax by default it is 150 ms but we can change it by debounce.
# wire:model.debounce.500ms

# it will not called ajax when we stop typing but when we click somewhere else or focused off then it will call.
# wire:model.lazy

# it will not send until we hit submit.
# wire:model.defer

# live automatically disabled button after submit form for some time. we will add opacityy to check whether it work or not.
class="disabled:opacity-50"

# now we will add spinner from tailwind css to the button and add these code soit will only spin when there some responce from the server and the responce is due to submitForm function in the controller.
wire:loading wire:target="submitForm"

# Ccontact form view

<div class="bg-white py-16 px-4 sm:px-6 lg:col-span-3 lg:py-24 lg:px-8 xl:pl-12">
    <div class="max-w-lg mx-auto lg:max-w-none">
        <form wire:submit.prevent="submitForm" action="/contact" method="POST" class="grid grid-cols-1 row-gap-6">
            @csrf

            @if ($successMessage)
                <div class="ml-3">
                    <p class="text-sm leading-5 font-medium text-green-800">
                        {{ $successMessage }}
                    </p>
                </div>
            @endif

            <div>
                <label for="name" class="sr-only">Full name</label>
                <div class="relative rounded-md shadow-sm">
                    <input wire:model="name" id="name" name="name" value="{{ old('name') }}"
                        class="@error('name')border border-red-500 @enderror form-input block w-full py-3 px-4 placeholder-gray-500 transition ease-in-out duration-150"
                        placeholder="Full name">
                </div>
                @error('name')
                <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="">
                <span class="inline-flex rounded-md shadow-sm">
                    <button type="submit"
                        class="inline-flex items-center justify-center py-3 px-6 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out disabled:opacity-50">
                        <svg wire:loading wire:target="submitForm" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span>Submit</span>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>

# use flash messeges of livewire for notification.
session()->flash('status', 'Post successfully updated.');

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

# contact form controller
# for validation we have to define rules function like here in controller.
# then we have a hook for updated just pass the variable ($propertyName) which is changed and will validate it there and update the view in real time so we can do real time validation on it.
# and if we want to validate total form then we can do it the submit function by $this->validate();

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Mail\ContactFormMailable;
use Illuminate\Support\Facades\Mail;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $message;
    public $successMessage;
    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required|min:5',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm()
    {
        $contact = $this->validate();

        // $contact['name'] = $this->name;
        // $contact['email'] = $this->email;
        // $contact['phone'] = $this->phone;
        // $contact['message'] = $this->message;

        sleep(1);
        Mail::to('andre@andre.com')->send(new ContactFormMailable($contact));

        $this->successMessage = 'We received your message successfully and will get back to you shortly!';
        // session()->flash('success_message', 'We received your message successfully and will get back to you shortly!');

        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}

# me can make laravel mail with email markdown on this command
php artisan make:mail ContactFormMailable --markdown=emails.contact-form-email

# mailable class
# call markdown in build function by markdown function.
class ContactFormMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->contact['email'])
            ->subject('Contact Form Submission')
            ->markdown('emails.contact-form-email');
    }
}

# email markdown
@component('mail::message')
# Contact Form Submission

From: {{ $contact['name'] }}

Email: {{ $contact['email'] }}

Phone: {{ $contact['phone'] }}

Message: {{ $contact['message'] }}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

----------------------------------------------------------------------------------------------------------------------------------------------

# Video 3 (Contact Form Testing)

# Testing Livewire components is a breeze. Let's have a look at testing our contact form and all of its features.

# livewire make testing very easily.
# For making test file use this command
php artisan make:test ExampleTest

# we have to test our component existance so we will write this function in our test file.
public function main_page_contains_contact_form_livewire_component()
{
    $this->get('/')
        ->assertSeeLivewire('contact-form');
}

# Then input this command
# Use for testign all files in unit folder and feature both.
php artisan test
# Test only specific files.
php artisan test --filter ContactFormTest
# Test specific function in specific files.
php artisan test --filter ContactFormTest::main_page_contains_contact_form_livewire_component

# Testing an email
# Make Fake Email
# Set all variable submit form.
# See messege notification.
# Send and email.
public function contact_form_sends_out_an_email()
{
    Mail::fake();

    Livewire::test(ContactForm::class)
        ->set('name', 'Andre')
        ->set('email', 'someguy@someguy.com')
        ->set('phone', '12345')
        ->set('message', 'This is my message.')
        ->call('submitForm')
        ->assertSee('We received your message successfully and will get back to you shortly!');

    Mail::assertSent(function (ContactFormMailable $mail) {
        $mail->build();

        return $mail->hasTo('andre@andre.com') &&
            $mail->hasFrom('someguy@someguy.com') &&
            $mail->subject === 'Contact Form Submission';
    });
}

# Testing validation
# name validation in form.
public function contact_form_name_field_is_required()
{
    Livewire::test(ContactForm::class)
        ->set('email', 'someguy@someguy.com')
        ->set('phone', '12345')
        ->set('message', 'This is my message.')
        ->call('submitForm')
        ->assertHasErrors(['name' => 'required']);
}

# Testing Email validation in form.
public function contact_form_email_field_is_required()
{
    Livewire::test(ContactForm::class)
        ->set('name', 'Andre')
        ->set('phone', '12345')
        ->set('message', 'This is my message.')
        ->call('submitForm')
        ->assertHasErrors(['email' => 'required']);
}

# Testing Email validation in form.
public function contact_form_email_field_fails_on_invalid_email()
{
    Livewire::test(ContactForm::class)
        ->set('name', 'Andre')
        ->set('email', 'notanemail')
        ->set('phone', '12345')
        ->set('message', 'This is my message.')
        ->call('submitForm')
        ->assertHasErrors(['email' => 'email']);
}

# Testing Phone validation in form.
public function contact_form_phone_field_is_required()
{
    Livewire::test(ContactForm::class)
        ->set('name', 'Andre')
        ->set('email', 'someguy@someguy.com')
        ->set('message', 'This is my message.')
        ->call('submitForm')
        ->assertHasErrors(['phone' => 'required']);
}

# Testing Messege validation in form.
public function contact_form_message_field_is_required()
{
    Livewire::test(ContactForm::class)
        ->set('name', 'Andre')
        ->set('email', 'someguy@someguy.com')
        ->set('phone', '12345')
        ->call('submitForm')
        ->assertHasErrors(['message' => 'required']);
}

# Testing Messege validation in form.
public function contact_form_message_field_has_minimum_characters()
{
    Livewire::test(ContactForm::class)
        ->set('message', 'abc')
        ->call('submitForm')
        ->assertHasErrors(['message' => 'min']);
}

----------------------------------------------------------------------------------------------------------------------------------------------

# Video 4 (Search Dropdown)

# Next up, let's use the iTunes API to build a search dropdown that allows us to search for songs and artists. As part of this example, we'll also review how to test it.

@if (strlen($search) > 2)
    @forelse ($searchResults as $result)
        {{ $reults }}
    @empty
        <li class="px-4 py-4">No results found for "{{ $search }}"</li>
    @endforelse
@endif

# best way to search is to check whether $search input must eb greatet than 2 charcter then should show us result adn search for it.
# use forelse when there is value so it will execute if empty then show no result

# component exist or not
public function main_page_contains_search_dropdown_livewire_component()
{
    $this->get('/')
        ->assertSeeLivewire('search-dropdown');
}

# if song exist then it search correctly or not.
public function search_dropdown_searches_correctly_if_song_exists()
{
    Livewire::test(SearchDropdown::class)
        ->assertDontSee('John Lennon')
        ->set('search', 'Imagine')
        ->assertSee('John Lennon');
}

# if song does not exist then it search correctl or not.
public function search_dropdown_shows_message_if_no_song_exists()
{
    Livewire::test(SearchDropdown::class)
        ->set('search', 'asfastejoaiestioaet')
        ->assertSee('No results found for');
}

----------------------------------------------------------------------------------------------------------------------------------------------
