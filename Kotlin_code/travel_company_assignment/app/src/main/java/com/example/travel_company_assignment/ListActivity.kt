package com.example.travel_company_assignment

import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.text.Html
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.AuthFailureError
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONArray
import org.json.JSONException

// This class is used for user/admin can read, like, dislike and delete reviews, and also read admin comment,  and admin can comment on review
class ListActivity: AppCompatActivity() {

    // Declaration of the variables and RecyclerView
    private lateinit
    var recyclerView: RecyclerView
    var btnAdd: Button ? = null;
    var logout: Button ? = null;
    var userProfileUpdate: Button ? = null;
    var username: TextView ? = null;

    override fun onCreate(savedInstanceState: Bundle ? ) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_list)

        // Get the user details through SharedPreference method from main activity
        val sharedPreferences = getSharedPreferences("UserDetails", Context.MODE_PRIVATE)
        val name = sharedPreferences.getString("email", "")

        btnAdd = findViewById < Button > (R.id.addReview)
        logout = findViewById < Button > (R.id.loginpage)
        userProfileUpdate = findViewById < Button > (R.id.userProfileUpdate)
        username = findViewById < TextView > (R.id.username)
        recyclerView = findViewById(R.id.recyclerView)
        recyclerView.layoutManager = LinearLayoutManager(this)

        // Setting user name in the page
        username!!.text = Html.fromHtml("Welcome <b>${name.toString()}</b>", Html.FROM_HTML_MODE_COMPACT)

        //Below function is to return to the log in page
        logout!!.setOnClickListener {
            Toast.makeText(this, "Logged out", Toast.LENGTH_LONG).show()
            val myIntent = Intent(this, MainActivity::class.java)
            startActivity(myIntent)
        }

        //Below function is used to direct to add review page
        btnAdd!!.setOnClickListener {
            val myIntent = Intent(this, addReviewPage::class.java)
            startActivity(myIntent)
        }

        //Below function is used to direct to user profile update page
        userProfileUpdate!!.setOnClickListener {
            val myIntent = Intent(this, profileUpdate::class.java)
            startActivity(myIntent)
        }

        // Below code is used to fetch all the reviews in the database through php
        val url = "http://192.168.0.13/mobilesoftware/link.php?op=1" // This is URL to for the API
        val loginUrl = url + "&get_data=1"

        val queue = Volley.newRequestQueue(this) // Create a Volley request queue

        // Create a string request for making a POST request
        val stringRequest = StringRequest(
            Request.Method.GET, // Specify the request method as GET
            loginUrl,
            {
                    response ->
                try {

                    // The response will have the all review in the database

                    // The response will fetch from the database as a JSON
                    val jsonArray = JSONArray(response)

                    // Create a Itemlist using mutableListOf Item
                    val itemList = mutableListOf < Item > ()

                    // fetching the review id, destination, review, user_id, comment, likecount and dislikecount for each review
                    for (i in 0 until jsonArray.length()) {

                        val jsonObject = jsonArray.getJSONObject(i)
                        val reviewid = jsonObject.getString("review_id")
                        val reviewdestination = jsonObject.getString("destination")
                        val reviewreview = jsonObject.getString("review")
                        val reviewuserid = jsonObject.getString("user_id")
                        val reviewcomment = jsonObject.getString("comment")
                        val reviewlikecount = jsonObject.getString("likecount")
                        val reviewdislikecount = jsonObject.getString("dislikecount")

                        val item = Item(reviewid, reviewdestination, reviewreview, reviewuserid, reviewcomment, reviewlikecount, reviewdislikecount)
                        itemList.add(item)

                    }

                    // send the reviews into the recycleAdapter to display in the recycler view
                    val adapter = recycleAdapter(itemList, applicationContext)
                    recyclerView.adapter = adapter

                } catch (e: JSONException) {
                    e.printStackTrace()
                }
            },
            {
                    error ->
                error.printStackTrace()
            })

        queue.add(stringRequest) // Add the string request to the request queue
    }

    // Create a Item class that has the arguments list of review details
    private data class Item(val id: String,
                            val destination: String,
                            val reviewreview: String,
                            val reviewuserid: String,
                            val reviewcomment: String,
                            val reviewlikecount: String,
                            val reviewdislikecount: String, )

    // recycleAdapter class is used to bind review details in the RecyclerView
    private class recycleAdapter(private val itemList: List < Item > , private val context: Context): RecyclerView.Adapter < MyViewHolder > () {

        // onCreateViewHolder: Called when a new ViewHolder object needs to be created
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MyViewHolder {
            val itemView = LayoutInflater.from(parent.context).inflate(R.layout.activity_review_list, parent, false)
            return MyViewHolder(itemView)
        }

        // onBindViewHolder: Called to bind data to a ViewHolder at the specified position
        override fun onBindViewHolder(holder: MyViewHolder, position: Int) {

            // Get the item at the current position from the itemList
            val item = itemList[position]

            // Get the user details through SharedPreference method from main activity
            val sharedPreferences = context.getSharedPreferences("UserDetails", Context.MODE_PRIVATE)
            val id = sharedPreferences.getString("id", "")
            val role = sharedPreferences.getString("role", "")

            // Set the review details to the TextViews in the activity_review_list
            holder.textViewId.text = Html.fromHtml("<b>Place : </b>${item.destination}<br><b>Review : </b>${item.reviewreview}", Html.FROM_HTML_MODE_COMPACT)
            holder.likecountView.text = "${item.reviewlikecount}"
            holder.dislikecountView.text = "${item.reviewdislikecount}"
            holder.admincomment.text = Html.fromHtml("<b>Admin Comment : </b>${item.reviewcomment}")

            // Fetching all review details through sharedPreferences method which can then used in other class
            val sharedPreferences1 = context.getSharedPreferences("review", Context.MODE_PRIVATE)
            val editor = sharedPreferences1.edit()
            editor.putString("reviewid", item.id)
            editor.putString("reviewdestination", item.destination)
            editor.putString("reviewreview", item.reviewreview)
            editor.putString("reviewuserid", item.reviewuserid)
            editor.putString("reviewcomment", item.reviewcomment)
            editor.putString("reviewlikecount", item.reviewlikecount)
            editor.putString("reviewdislikecount", item.reviewdislikecount)
            editor.apply()

            val reviewuserid = item.reviewuserid

            // check if Admin or user is logged in or not
            if (role == "admin") {

                // This will runs if admin is logged in
                // Below function is used to add admin comment to a review
                holder.admincommentbtn.setOnClickListener {

                    // Fetching admin comment
                    val admincommentbox: EditText = holder.itemView.findViewById(R.id.admincommentbox)

                    // check if admin comment is empty or not
                    if (admincommentbox.text?.isEmpty() == true) {
                        // admincommentbox is empty
                        // This will toast the message that The Admin Comment box is Empty
                        Toast.makeText(context, "The Admin Comment box is Empty", Toast.LENGTH_LONG).show()

                    } else {
                        // admincommentbox is not empty

                        val reviewid = item.id
                        val userid = reviewid.toString();

                        val comment = admincommentbox?.text.toString();

                        val url = "http://192.168.0.13/mobilesoftware/link.php?op=1"
                        val loginUrl = url + "&addcomment=1"

                        val queue = Volley.newRequestQueue(context); // Create a Volley request queue

                        // Create a string request for making a POST request
                        val stringRequest = object: StringRequest(
                            com.android.volley.Request.Method.POST, // Specify the request method as POST
                            loginUrl,
                            Response.Listener < String > {
                                    response ->
                                try {

                                    // This will toast the response
                                    Toast.makeText(context, response.toString(), Toast.LENGTH_LONG).show()

                                    // check if the response is "Admin comment Added" then redirect to the  list activity
                                    if (response.toString() == "Admin comment Added") {
                                        val intent = Intent(context, ListActivity::class.java)
                                        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                        context.startActivity(intent)

                                    }
                                } catch (e: JSONException) {
                                    e.printStackTrace()
                                }
                            },
                            object: Response.ErrorListener {
                                override fun onErrorResponse(error: VolleyError ? ) {
                                    if (error != null) {
                                        Toast.makeText(context, error.message, Toast.LENGTH_LONG).show()

                                    };
                                }
                            }) {

                            @Throws(AuthFailureError::class)
                            override fun getParams(): Map < String, String > {
                                val params = HashMap < String,
                                        String > ()
                                params["userid"] = userid // Set the userid parameter
                                params["comment"] = comment // Set the comment parameter
                                return params
                            }
                        }

                        queue.add(stringRequest) // Add the string request to the request queue
                    }
                }

            } else {

                // This will runs if user is logged in
                // The below code will Hide the admin comment box and admin comment button by setting its visibility to GONE
                holder.admincommentbtn.visibility = View.GONE
                holder.admincommentbox.visibility = View.GONE
            }

            // Below function is used to like the a review
            holder.likebtn.setOnClickListener {

                val reviewid = item.id
                val reviewlikecount = item.reviewlikecount
                var likeCount: Int = reviewlikecount?.toInt()?:0 // convert to integer, default to 0 if null or not a number
                likeCount += 1 // increment the value of likeCount by 1

                val userid = reviewid.toString()

                val url = "http://192.168.0.13/mobilesoftware/link.php?op=1"
                val loginUrl = url + "&likereview=1"

                val queue = Volley.newRequestQueue(context); // Create a Volley request queue

                // Create a string request for making a POST request
                val stringRequest = object: StringRequest(
                    com.android.volley.Request.Method.POST, // Specify the request method as POST
                    loginUrl,
                    Response.Listener < String > {
                            response ->
                        try {

                            // This will toast the response
                            Toast.makeText(context, response.toString(), Toast.LENGTH_LONG).show()

                            // check if the response is "Review Liked" then redirect to the  list activity
                            if (response.toString() == "Review Liked") {

                                val intent = Intent(context, ListActivity::class.java)
                                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                context.startActivity(intent)

                            }
                        } catch (e: JSONException) {
                            e.printStackTrace()
                        }
                    },
                    object: Response.ErrorListener {
                        override fun onErrorResponse(error: VolleyError ? ) {
                            if (error != null) {
                                Toast.makeText(context, error.message, Toast.LENGTH_LONG).show()

                            };
                        }
                    }) {

                    @Throws(AuthFailureError::class)
                    override fun getParams(): Map < String, String > {
                        val params = HashMap < String,
                                String > ()
                        params["userid"] = userid // Set the userid parameter
                        params["likecount"] = likeCount.toString() // Set the likecount parameter
                        return params
                    }
                }

                queue.add(stringRequest) // Add the string request to the request queue

            }

            // Below function is used to dislike the a review
            holder.dislikebtn.setOnClickListener {

                val reviewid = item.id
                val reviewdislikecount = item.reviewdislikecount
                var dislikeCount: Int = reviewdislikecount?.toInt()?:0 // convert to integer, default to 0 if null or not a number
                dislikeCount += 1 // increment the value of dislikeCount by 1

                val userid = reviewid.toString();

                val url = "http://192.168.0.13/mobilesoftware/link.php?op=1"
                val loginUrl = url + "&dislikereview=1"

                val queue = Volley.newRequestQueue(context); // Create a Volley request queue

                // Specify the request method as POST
                val stringRequest = object: StringRequest(
                    com.android.volley.Request.Method.POST, // Specify the request method as POST
                    loginUrl,
                    Response.Listener < String > {
                            response ->
                        try {
                            // This will toast the response
                            Toast.makeText(context, response.toString(), Toast.LENGTH_LONG).show()

                            // check if the response is "Review Disliked" then redirect to the  list activity
                            if (response.toString() == "Review Disliked") {

                                val intent = Intent(context, ListActivity::class.java)
                                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                context.startActivity(intent)

                            }
                        } catch (e: JSONException) {
                            e.printStackTrace()
                        }
                    },
                    object: Response.ErrorListener {
                        override fun onErrorResponse(error: VolleyError ? ) {
                            if (error != null) {
                                Toast.makeText(context, error.message, Toast.LENGTH_LONG).show()

                            };
                        }
                    }) {

                    @Throws(AuthFailureError::class)
                    override fun getParams(): Map < String, String > {
                        val params = HashMap < String,
                                String > ()
                        params["userid"] = userid // Set the userid parameter
                        params["dislikecount"] = dislikeCount.toString() // Set the dislikecount parameter
                        return params
                    }
                }

                queue.add(stringRequest) // Add the string request to the request queue

            }

            // Below function is used to update the review
            holder.btnUpdate.setOnClickListener {
                // check if logged in user/admin user_id and updating review's created user_id is same or not
                if (reviewuserid == id) {
                    val sharedPreferences1 = context.getSharedPreferences("review", Context.MODE_PRIVATE)
                    val editor = sharedPreferences1.edit()
                    editor.putString("reviewid", item.id)
                    editor.putString("reviewdestination", item.destination)
                    editor.putString("reviewreview", item.reviewreview)
                    editor.putString("reviewuserid", item.reviewuserid)
                    editor.putString("reviewcomment", item.reviewcomment)
                    editor.putString("reviewlikecount", item.reviewlikecount)
                    editor.putString("reviewdislikecount", item.reviewdislikecount)
                    editor.apply()

                    // If yes then it will direct to update_review class
                    val intent = Intent(context, update_review::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                    context.startActivity(intent)
                } else {

                    // This will toast the "This is not your review"
                    Toast.makeText(context, "This is not your review", Toast.LENGTH_LONG).show()
                }

            }

            // Below function is used to delete the review
            holder.btnDelete.setOnClickListener {
                // check if logged in user/admin user_id and deleting review's created user_id is same or not

                if (reviewuserid == id) {

                    // If yes then it will delete the review
                    //fetch the review id
                    val reviewid = item.id
                    val userid = reviewid.toString();

                    val url = "http://192.168.0.13/mobilesoftware/link.php?op=1"
                    val loginUrl = url + "&delete=1"

                    val queue = Volley.newRequestQueue(context); // Create a Volley request queue

                    // Create a string request for making a POST request
                    val stringRequest = object: StringRequest(
                        com.android.volley.Request.Method.POST,
                        loginUrl,
                        Response.Listener < String > {
                                response ->
                            try {

                                // This will toast the response
                                Toast.makeText(context, response.toString(), Toast.LENGTH_LONG).show()

                                // check if the response is "Record Deleted" then redirect to the list activity
                                if (response.toString() == "Record Deleted") {

                                    val intent = Intent(context, ListActivity::class.java)
                                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK
                                    context.startActivity(intent)

                                }

                            } catch (e: JSONException) {
                                e.printStackTrace()
                            }
                        },
                        object: Response.ErrorListener {
                            override fun onErrorResponse(error: VolleyError ? ) {
                                if (error != null) {
                                    Toast.makeText(context, error.message, Toast.LENGTH_LONG).show()

                                };
                            }
                        }) {

                        @Throws(AuthFailureError::class)
                        override fun getParams(): Map < String, String > {
                            val params = HashMap < String,
                                    String > ()
                            params["userid"] = userid // Set the userid parameter

                            return params
                        }
                    }

                    queue.add(stringRequest) // Add the string request to the request queue
                } else {

                    // This will toast the as "This is not your review"
                    Toast.makeText(context, "This is not your review", Toast.LENGTH_LONG).show()
                }

            }

        }

        // getItemCount: Returns the total number of items in the itemList
        override fun getItemCount() = itemList.size

    }

    // This class represents a single item view in the RecyclerView
    private class MyViewHolder(itemView: View): RecyclerView.ViewHolder(itemView) {

        // In this class, we have initialize and reference the views in the activity_review_list layout

        val textViewId: TextView = itemView.findViewById(R.id.textViewId)
        val likecountView: TextView = itemView.findViewById(R.id.likecountView)
        val dislikecountView: TextView = itemView.findViewById(R.id.dislikecountView)
        val btnDelete: Button = itemView.findViewById(R.id.reviewDelete)
        val btnUpdate: Button = itemView.findViewById(R.id.reviewUpdate)
        val likebtn: Button = itemView.findViewById(R.id.likebtn)
        val dislikebtn: Button = itemView.findViewById(R.id.dislikebtn)

        val admincomment: TextView = itemView.findViewById(R.id.admincomment)
        val admincommentbox: EditText = itemView.findViewById(R.id.admincommentbox)
        val admincommentbtn: Button = itemView.findViewById(R.id.admincommentbtn)

    }

}