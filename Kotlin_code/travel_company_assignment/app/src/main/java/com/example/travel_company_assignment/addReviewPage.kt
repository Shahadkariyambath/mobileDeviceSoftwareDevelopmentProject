package com.example.travel_company_assignment

import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import com.android.volley.AuthFailureError
import com.android.volley.Response
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException

// This class is used to add new review
class addReviewPage: AppCompatActivity() {

        // Declaration of the variables
        var btnAdd: Button ? = null;
        var editTextReviewPlace: EditText ? = null;
        var editTextReviewDetail: EditText ? = null;
        val url = "http://192.168.0.13/mobilesoftware/link.php?op=1" // This is URL to for the API

        override fun onCreate(savedInstanceState: Bundle ? ) {
                super.onCreate(savedInstanceState)
                setContentView(R.layout.activity_add_review_page)

                btnAdd = findViewById < Button > (R.id.addReview)

                // Get the user ID through SharedPreference method from main activity
                val sharedPreferences = getSharedPreferences("UserDetails", Context.MODE_PRIVATE)
                val id = sharedPreferences.getString("id", "")

                editTextReviewPlace = findViewById < EditText > (R.id.editTextReviewPlace)
                editTextReviewDetail = findViewById < EditText > (R.id.editTextReviewDetail)

                val logout = findViewById < Button > (R.id.loginpage)

                //Below function is to return to the log in page
                logout!!.setOnClickListener {
                        Toast.makeText(this, "Logged out", Toast.LENGTH_LONG).show()

                        val myIntent = Intent(this, MainActivity::class.java)
                        startActivity(myIntent)
                }

                // Below function is used to add a new review in the database through php
                btnAdd!!.setOnClickListener {
                        // Check if either review place or review is empty
                        if (editTextReviewPlace?.text?.isEmpty() == true || editTextReviewDetail?.text?.isEmpty() == true) {
                                Toast.makeText(this, "You need to fill both the Field", Toast.LENGTH_LONG).show()

                        } else {
                                // Fetching newly added place and review details
                                val addplace = editTextReviewPlace?.text.toString();
                                val addreview = editTextReviewDetail?.text.toString();

                                val adduserid = id.toString(); // Get the user ID value through SharedPreference method

                                val loginUrl = url + "&addreview=1"
                                val queue = Volley.newRequestQueue(this); // Create a Volley request queue

                                // Create a string request for making a POST request
                                val stringRequest = object: StringRequest(
                                        com.android.volley.Request.Method.POST, // Specify the request method as POST
                                        loginUrl,
                                        Response.Listener < String > {
                                                        response ->
                                                try {
                                                        Toast.makeText(this, "New review Added", Toast.LENGTH_LONG).show()
                                                        val myIntent = Intent(this, ListActivity::class.java)
                                                        startActivity(myIntent)
                                                } catch (e: JSONException) {
                                                        e.printStackTrace()
                                                }
                                        },
                                        object: Response.ErrorListener {
                                                override fun onErrorResponse(error: VolleyError ? ) {
                                                        if (error != null) {
                                                                Toast.makeText(applicationContext, error.message, Toast.LENGTH_LONG).show()
                                                        };
                                                }
                                        }) {

                                        @Throws(AuthFailureError::class)
                                        override fun getParams(): Map < String, String > {
                                                val params = HashMap < String,
                                                        String > ()
                                                params["place"] = addplace // Set the place parameter
                                                params["review"] = addreview // Set the review parameter
                                                params["userid"] = adduserid // Set the userid paramete

                                                return params // Return the parameters
                                        }
                                }

                                queue.add(stringRequest) // Add the string request to the request queue
                        }

                }

        }
}