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

// This class is used to update reviews

class update_review: AppCompatActivity() {

    // Declaration of the variables
    var btnAdd: Button ? = null;
    var editUpdateTextReviewPlace: EditText ? = null;
    var editUpdateTextReviewDetail: EditText ? = null;

    val url = "http://192.168.0.13/mobilesoftware/link.php?op=1" // This is URL to for the API

    override fun onCreate(savedInstanceState: Bundle ? ) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_update_review)

        btnAdd = findViewById < Button > (R.id.updateview)

        val logout = findViewById < Button > (R.id.loginpage)

        //Below function is to return to the log in page
        logout!!.setOnClickListener {

            Toast.makeText(this, "Logged out", Toast.LENGTH_LONG).show()

            val myIntent = Intent(this, MainActivity::class.java)
            startActivity(myIntent)
        }

        // Get the review details through SharedPreference method from List activity
        val sharedPreferences1 = getSharedPreferences("review", Context.MODE_PRIVATE)
        val reviewid = sharedPreferences1.getString("reviewid", "")
        val reviewdestination = sharedPreferences1.getString("reviewdestination", "")
        val reviewreview = sharedPreferences1.getString("reviewreview", "")

        editUpdateTextReviewPlace = findViewById < EditText > (R.id.editUpdateTextReviewPlace)
        editUpdateTextReviewDetail = findViewById < EditText > (R.id.editUpdateTextReviewDetail)

        // Setting review details in the page
        editUpdateTextReviewPlace?.setText(reviewdestination)
        editUpdateTextReviewDetail?.setText(reviewreview)

        // Below function is used to update the review in the database through php
        btnAdd!!.setOnClickListener {

            // Check if place name and review is empty or null
            if(editUpdateTextReviewPlace?.text.isNullOrEmpty() || editUpdateTextReviewDetail?.text.isNullOrEmpty()) {
                // This will toast the message "All field should be filled"
                Toast.makeText(this, "All field should be filled", Toast.LENGTH_LONG).show()
            }
            else
            {
                // Fetching updated place and review
                val place = editUpdateTextReviewPlace?.text.toString();
                val review = editUpdateTextReviewDetail?.text.toString();

                val id = reviewid.toString(); // Get the review ID value through SharedPreference method

                val loginUrl = url + "&updateReview=1"
                val queue = Volley.newRequestQueue(this); // Create a Volley request queue

                // Create a string request for making a POST request
                val stringRequest = object: StringRequest(
                    com.android.volley.Request.Method.POST, // Specify the request method as POST
                    loginUrl,
                    Response.Listener < String > {
                            response ->
                        try {
                            Toast.makeText(this, "Review updated", Toast.LENGTH_LONG).show()
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
                        params["destination"] = place // Set the destination parameter
                        params["review"] = review // Set the review parameter
                        params["id"] = id // Set the id parameter

                        return params // Return the parameters
                    }
                }

                queue.add(stringRequest) // Add the string request to the request queue
            }

        }

    }
}