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

// This class is used to update user profile
class profileUpdate: AppCompatActivity() {

    // Declaration of the variables
    var btnAdd: Button ? = null;
    var editUpdateTextName: EditText ? = null;
    var editUpdateTextAge: EditText ? = null;
    var editUpdateTextMobile: EditText ? = null;
    var editUpdateTextPassword: EditText ? = null;
    var editUpdateTextConfirmPassword: EditText ? = null;

    val url = "http://192.168.0.13/mobilesoftware/link.php?op=1" // This is URL to for the API

    override fun onCreate(savedInstanceState: Bundle ? ) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile_update)

        // Get the user details through SharedPreference method from main activity
        val sharedPreferences = getSharedPreferences("UserDetails", Context.MODE_PRIVATE)
        val id = sharedPreferences.getString("id", "")
        val name = sharedPreferences.getString("email", "")
        val age = sharedPreferences.getString("age", "")
        val mobile = sharedPreferences.getString("mobile", "")

        btnAdd = findViewById < Button > (R.id.updateUser)
        editUpdateTextName = findViewById < EditText > (R.id.editUpdateTextName)
        editUpdateTextAge = findViewById < EditText > (R.id.editUpdateTextAge)
        editUpdateTextMobile = findViewById < EditText > (R.id.editUpdateTextMobile)
        editUpdateTextPassword = findViewById < EditText > (R.id.editUpdateTextPassword)
        editUpdateTextConfirmPassword = findViewById < EditText > (R.id.editUpdateTextConfirmPassword)

        // Setting user details in the page
        editUpdateTextName?.setText(name)
        editUpdateTextAge?.setText(age)
        editUpdateTextMobile?.setText(mobile)

        //Below function is to return to the log in page
        val logout = findViewById < Button > (R.id.loginpage)
        logout!!.setOnClickListener {
            Toast.makeText(this, "Logged out", Toast.LENGTH_LONG).show()

            val myIntent = Intent(this, MainActivity::class.java)
            startActivity(myIntent)
        }

        // Below function is used to update the user details in the database through php
        btnAdd!!.setOnClickListener {

            // Fetching updated password and confirm password
            val textpassword = editUpdateTextPassword?.text.toString();
            val textconfimrPassword = editUpdateTextConfirmPassword?.text.toString();

            // Check if username or age or mobile number is empty or null
            if(editUpdateTextName?.text.isNullOrEmpty() || editUpdateTextAge?.text.isNullOrEmpty() || editUpdateTextMobile?.text.isNullOrEmpty()){
                // This will toast the message "All field should be filled"
                Toast.makeText(this, "All field should be filled", Toast.LENGTH_LONG).show()
            }else
            // Check if either password and confirm password is empty or null
            if (textpassword.isNullOrEmpty() && textconfimrPassword.isNullOrEmpty()) {
                // This part of function will execute when the user need to change with user details without password

                // Fetching updated name, age and mobile number
                val textName = editUpdateTextName?.text.toString();
                val textAge = editUpdateTextAge?.text.toString();
                val textMobile = editUpdateTextMobile?.text.toString();

                val textid = id.toString();
                val loginUrl = url + "&updateUser=1"

                val queue = Volley.newRequestQueue(this); // Create a Volley request queue

                // Create a string request for making a POST request
                val stringRequest = object: StringRequest(
                    com.android.volley.Request.Method.POST, // Specify the request method as POST
                    loginUrl,
                    Response.Listener < String > {
                            response ->
                        try {
                            // This will toast the message that Profile Updated
                            Toast.makeText(this, "Profile Updated", Toast.LENGTH_LONG).show()
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
                        params["name"] = textName // Set the name parameter
                        params["age"] = textAge // Set the age parameter
                        params["mobile"] = textMobile // Set the mobile parameter
                        params["id"] = textid // Set the review id parameter

                        return params
                    }
                }
                queue.add(stringRequest) // Add the string request to the request queue

            } else
            // check if password and confirm password is equal or not
                if (textpassword.equals(textconfimrPassword)) {

                    // This part of function will execute when the user need to change the password along with user details

                    // Fetching updated name, age and mobile number
                    val textName = editUpdateTextName?.text.toString();
                    val textAge = editUpdateTextAge?.text.toString();
                    val textMobile = editUpdateTextMobile?.text.toString();

                    val textid = id.toString();
                    val loginUrl = url + "&updateUserwithPassword=1"

                    val queue = Volley.newRequestQueue(this); // Create a Volley request queue

                    // Create a string request for making a POST request

                    val stringRequest = object: StringRequest(
                        com.android.volley.Request.Method.POST, // Specify the request method as POST
                        loginUrl,
                        Response.Listener < String > {
                                response ->
                            try {
                                // This will toast the message that Profile updated
                                Toast.makeText(this, "Profile Updated with password", Toast.LENGTH_LONG).show()
                                val myIntent = Intent(this, ListActivity::class.java)
                                startActivity(myIntent)
                            } catch (e: JSONException) {
                                e.printStackTrace()
                            }
                        },
                        object: Response.ErrorListener { // Response error will catch here
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
                            params["name"] = textName // Set the name parameter
                            params["age"] = textAge // Set the age parameter
                            params["mobile"] = textMobile // Set the mobile parameter
                            params["password"] = textpassword // Set the password parameter
                            params["id"] = textid // Set the review id parameter

                            return params // Return the parameters
                        }
                    }
                    queue.add(stringRequest) // Add the string request to the request queue

                } else {
                    // This will toast the message when password and confirm password is not same
                    Toast.makeText(this, "Both Password Should be same", Toast.LENGTH_LONG).show()
                }

        }

    }
}