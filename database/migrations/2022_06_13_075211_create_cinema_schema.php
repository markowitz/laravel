<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function($table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('description');
            $table->string('start_date');
            $table->string('end_date');
            $table->timestamps();
        });

        Schema::create('showrooms', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        //Seating table comes with type to know if it's vip and a surcharge_percentage column to
        //store perctange for the seat
        Schema::create('seating', function($table) {
            $table->increments('id');
            $table->string('position');
            $table->string('type');
            $table->string('surcharge_percentage');
            $table->timestamps();
        });

        //Tickets table comes with the time slot for each ticket
        Schema::create('tickets', function($table) {
            $table->increments('id');
            $table->string('time');
            $table->bigInteger('quantity_available');
            $table->decimal('price', 13, 2);
            $table->integer('showroom_id')->unsigned();
            $table->foreign('showroom_id')->references('id')->on('showrooms');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->timestamps();
        });

        //Bookings table to store the user bookings
        Schema::create('bookings', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->integer('ticket_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->integer('seating_id')->unsigned();
            $table->foreign('seating_id')->references('id')->on('seating');
            $table->decimal('total_amount', 13, 2);
            $table->timestamps();
        });

        //Pivot table to store booked seats
        Schema::create('booked_seating', function($table) {
            $table->integer('booking_id')->unsigned();
            $table->integer('seating_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('seating_id')->references('id')->on('seating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
