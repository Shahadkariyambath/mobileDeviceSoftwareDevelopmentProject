package com.example.travel_company_assignment

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import com.android.volley.AuthFailureError
import com.android.volley.Response
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException
import org.json.JSONObject

// This class is used for user/admin to login in
class MainActivity: AppCompatActivity() {

    // Declaration of the variables
    var editEmail: EditText ? = null;
    var editPassword: EditText ? = null;
    var btnLogIn: Button ? = null;
    var textDisplay: TextView ? = null;

    val url = "http://192.168.0.13/mobilesoftware/link.php?op=1" // This is URL to for the API

    override fun onCreate(savedInstanceState: Bundle ? ) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        editEmail = findViewById < EditText > (R.id.editEmail)
        editPassword = findViewById < EditText > (R.id.editPassword)
        btnLogIn = findViewById < Button > (R.id.btnLogIn)
        textDisplay = findViewById < TextView > (R.id.textViewLogin)

        // Below function is used to check the user or admin credential in the database and direct to the next page
        btnLogIn!!.setOnClickListener {

            // Fetching username and password
            val valueEmail = editEmail?.text.toString();
            val valuePassword = editPassword?.text.toString();

            val loginUrl = url + "&login=1"

            val queue = Volley.newRequestQueue(this); // Create a Volley request queue

            // Create a string request for making a POST request

            val stringRequest = @SuppressLint("SuspiciousIndentation")
            object: StringRequest(
                com.android.volley.Request.Method.POST, // Specify the request method as POST
                loginUrl,
                Response.Listener < String > {
                        response ->
                    try {

                        // The logged in user or admin details are fetching in the below response

                        // The response will fetch from the database as a JSON
                        val obj = JSONObject(response)

                        // Form the response, the result is extracted in viewAllranksArray
                        val viewAllranksArray = obj.getJSONArray("result")

                        for (i in 0..viewAllranksArray.length() - 1) {

                            // fetching the id, name as email, password, role, age and mobile
                            val ObjectsInranksArray = viewAllranksArray.getJSONObject(i)
                            val id = ObjectsInranksArray.getString("id")
                            val email = ObjectsInranksArray.getString("email");
                            val password = ObjectsInranksArray.getString("password");
                            val role = ObjectsInranksArray.getString("role");
                            val age = ObjectsInranksArray.getString("age");
                            val mobile = ObjectsInranksArray.getString("mobile");

                            // check if logged in user/admin entered username and password, and the database username and password is equal or not
                            if (valueEmail == email && valuePassword == password) {

                                // This will toast the message that Login Success
                                Toast.makeText(applicationContext, "Login Success", Toast.LENGTH_LONG).show()

                                // Fetching all user/admin details through sharedPreferences method which can used in other class
                                val sharedPreferences = getSharedPreferences("UserDetails", Context.MODE_PRIVATE)
                                val editor = sharedPreferences.edit()
                                editor.putString("id", id)
                                editor.putString("email", email)
                                editor.putString("password", password)
                                editor.putString("role", role)
                                editor.putString("age", age)
                                editor.putString("mobile", mobile)
                                editor.apply()

                                // Intent to next page
                                val myIntent = Intent(this, ListActivity::class.java)
                                startActivity(myIntent)
                            } else { // if the credentials are wrong

                                // This will toast the message that Wrong Credential
                                Toast.makeText(applicationContext, "Sorry, Wrong Credential", Toast.LENGTH_LONG).show()
                            }
                        }

                    } catch (e: JSONException) {
                        e.printStackTrace()
                        val jsonObject = JSONObject(response)
                        val message = jsonObject.getString("message")

                        Toast.makeText(applicationContext, message, Toast.LENGTH_LONG).show()

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
                    params["email"] = valueEmail.lowercase() // Set the email parameter
                    params["password"] = valuePassword.lowercase() // Set the password parameter

                    return params // Return the parameters
                }
            }

            queue.add(stringRequest) // Add the string request to the request queue

        }
    }

}