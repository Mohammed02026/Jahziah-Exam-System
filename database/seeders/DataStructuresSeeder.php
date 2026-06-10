<?php

namespace Database\Seeders;

use App\Enums\Difficulty;
use App\Enums\QuestionType;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DataStructuresSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::query()->where('role', 'instructor')->first();

        if (!$instructor) {
            return;
        }

        $course = Course::firstOrCreate(
            ['name' => 'Data Structures'],
            ['description' => 'Data Structures course']
        );

        $topicsData = [
            ['name' => 'Introduction & Time Complexity', 'description' => 'Big-O, time and space complexity'],
            ['name' => 'Arrays & Linked Lists', 'description' => 'Arrays and Linked Lists'],
            ['name' => 'Stack & Queue', 'description' => 'Stack, Queue, Deque'],
            ['name' => 'Trees', 'description' => 'Trees, BST, Traversals'],
            ['name' => 'Heap & Priority Queue', 'description' => 'Heap and Priority Queue'],
            ['name' => 'Graphs', 'description' => 'Graphs, BFS, DFS'],
            ['name' => 'Hashing', 'description' => 'Hash Tables and collisions'],
        ];

        $topics = [];
        foreach ($topicsData as $topicData) {
            $topics[] = Topic::firstOrCreate(
                ['course_id' => $course->id, 'name' => $topicData['name']],
                ['description' => $topicData['description']]
            );
        }

        foreach ($topics as $topic) {
            Lesson::updateOrCreate(
                ['topic_id' => $topic->id, 'title' => "Overview: {$topic->name}"],
                ['content' => $this->buildTopicLessonContent($topic->name)]
            );
        }

        $q1 = Question::firstOrCreate(
            ['topic_id' => $topics[0]->id, 'body' => 'What does O(n) mean in Big-O notation?'],
            [
                'type' => QuestionType::MCQ->value,
                'difficulty' => Difficulty::Easy->value,
                'marks' => 1,
                'created_by' => $instructor->id,
            ]
        );
        $q1->options()->delete();
        $q1->options()->createMany([
            ['text' => 'Execution time grows linearly with n', 'is_correct' => true],
            ['text' => 'Execution time is constant', 'is_correct' => false],
            ['text' => 'Execution time grows with n^2', 'is_correct' => false],
            ['text' => 'Execution time is logarithmic', 'is_correct' => false],
        ]);

        $q2 = Question::firstOrCreate(
            ['topic_id' => $topics[2]->id, 'body' => 'Which data structure follows LIFO?'],
            [
                'type' => QuestionType::MCQ->value,
                'difficulty' => Difficulty::Easy->value,
                'marks' => 1,
                'created_by' => $instructor->id,
            ]
        );
        $q2->options()->delete();
        $q2->options()->createMany([
            ['text' => 'Queue', 'is_correct' => false],
            ['text' => 'Stack', 'is_correct' => true],
            ['text' => 'Heap', 'is_correct' => false],
            ['text' => 'Graph', 'is_correct' => false],
        ]);

        $q3 = Question::firstOrCreate(
            ['topic_id' => $topics[1]->id, 'body' => 'Arrays have fixed size in most programming languages.'],
            [
                'type' => QuestionType::TrueFalse->value,
                'difficulty' => Difficulty::Easy->value,
                'marks' => 1,
                'created_by' => $instructor->id,
            ]
        );
        $q3->options()->delete();
        $q3->options()->createMany([
            ['text' => 'True', 'is_correct' => true],
            ['text' => 'False', 'is_correct' => false],
        ]);

        $exam = Exam::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Data Structures Exam - 1'],
            [
                'duration_minutes' => 15,
                'status' => 'published',
                'created_by' => $instructor->id,
                'total_marks' => 0,
            ]
        );

        $exam->questions()->syncWithoutDetaching([
            $q1->id => ['order' => 1, 'marks' => 1],
            $q2->id => ['order' => 2, 'marks' => 1],
            $q3->id => ['order' => 3, 'marks' => 1],
        ]);

        $exam->total_marks = (int) $exam->questions()->sum('exam_questions.marks');
        $exam->save();
    }

    private function buildTopicLessonContent(string $topicName): string
    {
        return match ($topicName) {
            'Introduction & Time Complexity' => <<<TEXT
Introduction & Time Complexity
This lesson introduces the idea of algorithm analysis.
Algorithm analysis helps us understand how efficient a solution is.
Efficiency is important when the input size becomes large.
A program may work correctly and still be too slow.
A program may also use too much memory.
That is why we study time complexity and space complexity.
Time complexity describes how running time grows.
It does not focus on exact seconds.
It focuses on growth behavior as input size increases.
Input size is usually represented by n.
The symbol n may mean number of elements.
The symbol n may mean size of an array.
The symbol n may mean number of nodes in a graph.
The symbol n may mean length of a string.
The exact meaning depends on the problem.
The important idea is that n represents scale.
When n grows, the work of the algorithm also changes.
Some algorithms grow slowly.
Some algorithms grow very quickly.
Algorithms that grow slowly are usually more scalable.
Scalability matters in real applications.
A small dataset may hide performance problems.
A huge dataset reveals performance problems very clearly.
This is why complexity matters in engineering.
Complexity helps compare two solutions fairly.
It removes dependence on machine speed.
A fast computer can still struggle with a bad algorithm.
A good algorithm can perform well even on modest hardware.
We therefore study how the work grows.
This is where asymptotic analysis appears.
Asymptotic analysis studies behavior as n becomes large.
It ignores tiny details that matter less at scale.
It looks at the dominant growth pattern.
This makes comparison simpler and more useful.
Big-O notation is the most common notation.
Big-O gives an upper bound on growth.
It describes how bad the growth can become.
It is often used for worst-case analysis.
Worst-case analysis is useful for guarantees.
A guaranteed upper bound helps in system design.
If a system must respond quickly, worst case matters.
Big-O is not the only notation.
There is also Big-Omega.
Big-Omega describes a lower bound.
There is also Big-Theta.
Big-Theta describes a tight bound.
In many courses Big-O is emphasized first.
This is because it is practical and common.
When we say an algorithm is O(n), we mean its work grows linearly.
When we say O(1), we mean constant growth.
When we say O(log n), growth is very slow.
When we say O(n log n), growth is moderate.
When we say O(n^2), growth is much faster.
When we say O(2^n), growth becomes explosive.
Exponential complexity is dangerous for large n.
Factorial complexity is even worse.
We usually try to avoid such growth when possible.
Now let us look at common complexities one by one.
O(1) is constant time.
Constant time does not mean zero time.
It means the amount of work does not depend on n.
Accessing an array element by index is often O(1).
Getting the first element of a list may be O(1).
Pushing onto a stack is often O(1).
Checking whether a variable is null is O(1).
These operations are simple and direct.
O(log n) is logarithmic time.
Logarithmic growth is excellent for large inputs.
Binary search is a classic O(log n) algorithm.
It works by cutting the search space in half.
Each step removes half the remaining possibilities.
This is why the number of steps is small.
If n is 1024, log2(n) is only 10.
That means only about 10 decisions are needed.
This is much better than checking every element.
O(n) is linear time.
Linear growth means work increases in direct proportion to n.
A single loop over an array is often O(n).
Counting elements is O(n).
Finding the maximum value by scanning all items is O(n).
Linear search in an unsorted list is O(n).
This is acceptable for moderate sizes.
But for huge datasets it may still be costly.
O(n log n) is a very important complexity.
Efficient comparison-based sorting often has this complexity.
Merge sort is O(n log n).
Heap sort is O(n log n).
Quicksort is average-case O(n log n).
This complexity is usually considered good for sorting.
It is larger than O(n).
It is much better than O(n^2).
O(n^2) is quadratic time.
Quadratic growth often comes from nested loops.
Comparing every pair of elements is O(n^2).
Simple sorting methods like bubble sort are O(n^2).
Selection sort is O(n^2).
Insertion sort can also be O(n^2) in the worst case.
Quadratic growth becomes expensive as n grows.
If n doubles, the work becomes about four times larger.
This can quickly become a problem.
O(n^3) is cubic time.
Triple nested loops often produce O(n^3).
Some matrix algorithms may have cubic behavior.
Cubic growth is usually too expensive for large inputs.
O(2^n) is exponential time.
Recursive brute-force solutions often produce O(2^n).
A common example is naive recursion for Fibonacci.
At small n it may seem acceptable.
At larger n it becomes very slow.
This is why optimization techniques matter.
Memoization can reduce repeated work.
Dynamic programming often transforms exponential solutions into polynomial ones.
Understanding complexity helps us find such improvements.
Now let us discuss how Big-O is simplified.
Suppose an algorithm does 3n + 5 operations.
We write this as O(n).
We ignore the constant 3.
We also ignore the constant 5.
Why do we do this.
Because at large n the linear part dominates.
The constant part matters less and less.
Suppose another algorithm does n^2 + n + 7 operations.
We write this as O(n^2).
The quadratic term dominates the linear term.
The constant again becomes unimportant.
This simplification makes comparison easier.
It also highlights the real scaling behavior.
Constants are not useless in practice.
They may matter for small inputs.
But asymptotic analysis focuses on large-scale behavior.
That is why constants are ignored in Big-O.
Another important idea is best case.
Best case means the most favorable input arrangement.
An algorithm may finish very quickly in best case.
But best case does not always help design robust systems.
Average case is also important.
Average case estimates typical performance.
It depends on assumptions about input distribution.
Worst case is often the safest measure.
It guarantees that performance will not exceed a certain growth.
This is why worst case is frequently used.
Now let us move to space complexity.
Space complexity measures memory usage growth.
It includes extra memory used by the algorithm.
Sometimes it includes input storage depending on convention.
Usually we focus on auxiliary space.
Auxiliary space means extra space beyond the input itself.
An algorithm that uses a few variables is O(1) space.
An algorithm that creates another array of size n is O(n) space.
Recursive algorithms also use stack space.
Deep recursion may require O(n) call stack memory.
This is important for both correctness and performance.
An algorithm may be fast but memory-heavy.
Another algorithm may be slower but memory-efficient.
Engineering often requires trade-offs.
Sometimes we choose faster time.
Sometimes we choose lower memory.
The correct choice depends on the system requirements.
Now let us look at loops and complexity.
A single loop from 1 to n is usually O(n).
Two consecutive loops from 1 to n are still O(n).
Why are they still O(n).
Because n + n equals 2n.
And Big-O ignores constant factors.
Two nested loops from 1 to n are O(n^2).
This is because each outer iteration performs n inner operations.
That gives n multiplied by n.
If the inner loop runs only half the time, it is still O(n^2).
Big-O cares about the dominant growth.
Now consider logarithmic loops.
If a loop doubles i each time, complexity is often O(log n).
If a loop halves n each time, complexity is often O(log n).
These patterns appear in binary search and divide-and-conquer methods.
Recognizing such patterns is a key skill.
Now let us think about recursion.
Recursion does not automatically mean exponential complexity.
This is a common misunderstanding.
The complexity depends on the recurrence relation.
Binary search is recursive and O(log n).
Merge sort is recursive and O(n log n).
Naive Fibonacci is recursive and exponential.
So recursion alone tells us nothing final.
We must analyze the actual recursive work.
Sometimes we use recurrence equations.
Sometimes we use the Master Theorem.
Sometimes we reason directly using trees of calls.
All of these help estimate growth.
Now consider input characteristics.
Complexity can depend on whether data is sorted.
Insertion sort performs better on nearly sorted data.
Binary search requires sorted data.
Hash table performance depends on collisions.
Graph algorithms depend on number of vertices and edges.
This means one symbol n is not always enough.
Sometimes we use both V and E.
Sometimes we use both m and n.
For example BFS is O(V + E).
This means work depends on vertices and edges together.
Precise notation gives clearer understanding.
Now let us study examples from daily programming.
Reading all characters in a string is O(n).
Checking whether a value exists in an unsorted list is O(n).
Looking up a value in a balanced BST is O(log n) on average.
Looking up a value in a hash table is often O(1) average case.
Sorting a list is usually O(n log n).
Generating every subset of a set is O(2^n).
Comparing every pair of students is O(n^2).
These examples show complexity in practice.
Now consider algorithm design choices.
Suppose you need membership checking many times.
A list may cost O(n) per check.
A hash set may cost average O(1) per check.
Choosing the right structure changes the total complexity dramatically.
Suppose you repeatedly need the smallest item.
A heap may be better than sorting every time.
Suppose you need prefix sums.
Preprocessing can reduce repeated query cost.
Complexity is therefore linked to design strategy.
It is not only about code syntax.
It is about the shape of the solution.
Now let us discuss amortized analysis.
Some operations are usually cheap but occasionally expensive.
Dynamic arrays are a classic example.
Appending is usually O(1).
But sometimes resizing occurs and copying is required.
That resize operation may take O(n).
Even so, average append over many operations is amortized O(1).
This is very useful in practical analysis.
It prevents us from overestimating repeated operations.
Now let us discuss why exact timing is not enough.
Exact timing depends on processor speed.
It depends on memory hierarchy.
It depends on language implementation.
It depends on compiler optimization.
It depends on background processes.
All of this makes exact timing unstable as a comparison tool.
Complexity abstracts away from these details.
It allows reasoning before implementation.
It also helps predict scaling before deployment.
Now let us connect complexity to interviews.
Interview problems often test complexity awareness.
A correct answer may still be considered weak if it is too slow.
Candidates are often asked for both solution and complexity.
This encourages algorithmic thinking.
But complexity is not only for interviews.
It is deeply useful in real software work.
Database queries have performance costs.
API endpoints must scale under heavy load.
Data pipelines process huge inputs.
Mobile apps must conserve memory and battery.
Game systems require fast updates.
Complexity thinking helps in all such areas.
Now let us look at common mistakes.
One mistake is assuming nested loops always mean O(n^2).
This is not always true.
If one loop runs m times and the other runs n times, complexity is O(mn).
If the inner loop shrinks quickly, the total may be smaller.
Another mistake is ignoring data structure operations.
Calling contains on a list inside a loop may create O(n^2) behavior.
Using a hash set may reduce it to O(n).
Another mistake is confusing average and worst case.
Hash tables are often O(1) average but can degrade in worst case.
Another mistake is assuming recursion is always slow.
Well-designed recursion can be efficient.
Now let us reflect on the learning goal.
The goal is not to memorize symbols only.
The goal is to reason about algorithm growth.
You should be able to inspect code and estimate behavior.
You should identify loops, recursion, data structure costs, and dominant terms.
You should compare alternative solutions.
You should understand when an algorithm is acceptable and when it is not.
You should know that small test cases can be misleading.
You should know that scale changes everything.
This is the central idea of time complexity.
A simple algorithm may be enough for small n.
A sophisticated algorithm may be required for huge n.
Good engineering means choosing appropriately.
Not every problem needs the fastest theoretical solution.
But every important system needs awareness of cost.
Complexity gives that awareness.
When you study algorithms, always ask how the work grows.
When you write code, ask how the input may scale.
When you choose a structure, ask what operations dominate.
When you optimize, target the expensive growth pattern.
This way complexity becomes a practical tool.
It helps you build better software.
It helps you explain design decisions.
It helps you avoid future bottlenecks.
It helps you think like an engineer.
This is why Introduction and Time Complexity is a foundational lesson.
It is not only theory.
It is a way of thinking about solutions.
Once you understand this lesson, many later topics become easier.
Sorting makes more sense.
Searching makes more sense.
Trees and graphs make more sense.
Hashing choices become easier to evaluate.
Dynamic programming improvements become easier to appreciate.
Complexity is therefore one of the first doors into algorithmic mastery.
Read the patterns carefully.
Practice analyzing simple code examples.
Then compare multiple solutions to the same problem.
With repetition, complexity analysis becomes natural.
That is the real goal of this topic.
A student who understands complexity can make better choices.
A student who ignores complexity may write code that fails at scale.
So learn to read growth, not just syntax.
Learn to think beyond small examples.
Learn to ask what happens when n becomes huge.
That question is the heart of algorithm analysis.
And that question is why time complexity matters.
TEXT,

            'Arrays & Linked Lists' => <<<TEXT
Arrays & Linked Lists

Arrays & Linked Lists are two of the most fundamental data structures in computer science.
They are used to store collections of elements.
Understanding the difference between them is very important for problem solving.

An array stores elements in contiguous memory.
This means all elements are placed next to each other in memory.
Because of this, arrays allow fast access using an index.
Accessing an element like arr[i] is usually O(1).
This is called random access.

Random access means you can jump directly to any position.
You do not need to visit previous elements.
This makes arrays very efficient for reading data.
For example, accessing the 100th element is as fast as accessing the first.

Arrays are simple and widely used.
Most programming languages support arrays directly.
They are used in many algorithms and data structures.

However, arrays have limitations.
One limitation is fixed size in some languages.
You must define the size before using the array.
If the array becomes full, you may need to create a new larger array.
This process involves copying elements.
Copying elements takes O(n) time.

Dynamic arrays solve this problem.
Dynamic arrays automatically resize when needed.
Examples include ArrayList in Java and vector in C++.
Resizing usually doubles the size.
This keeps append operations efficient on average.

Appending to a dynamic array is usually O(1) amortized.
Occasionally it becomes O(n) during resizing.
But over many operations, the average cost stays low.

Another limitation of arrays is insertion.
Inserting at the beginning requires shifting elements.
All elements must move one position forward.
This operation is O(n).

Similarly, deleting an element requires shifting.
Removing an element from the middle also costs O(n).
This makes arrays inefficient for frequent insertions and deletions.

Now let us discuss linked lists.
A linked list stores elements in nodes.
Each node contains data and a reference.
The reference points to the next node.

Nodes are not stored in contiguous memory.
They can be scattered anywhere in memory.
This makes linked lists flexible in size.
You do not need to predefine capacity.

There are different types of linked lists.
A singly linked list has one pointer per node.
Each node points to the next node.
The last node points to null.

A doubly linked list has two pointers per node.
Each node points to the next and previous nodes.
This allows traversal in both directions.

There is also a circular linked list.
In this structure, the last node points back to the first.
This creates a loop.

Linked lists have different strengths compared to arrays.
Insertion at the beginning is O(1).
You only change the head pointer.
No shifting is required.

Deletion at the beginning is also O(1).
You simply move the head pointer forward.

Insertion in the middle can be efficient if you have the reference.
You only update pointers.
This can also be O(1).

However, finding a position requires traversal.
To access the i-th element, you must walk through nodes.
This takes O(n) time.

This is the main weakness of linked lists.
They do not support random access.
You cannot jump directly to an element.

Linked lists also use extra memory.
Each node stores a pointer.
This increases memory usage compared to arrays.

Arrays are more cache-friendly.
Because elements are contiguous, CPU caching works better.
This improves performance in practice.

Linked lists are less cache-friendly.
Nodes are scattered in memory.
This may cause slower performance despite similar complexity.

Choosing between arrays and linked lists depends on use case.
If you need fast access by index, use arrays.
If you need frequent insertions and deletions, consider linked lists.

If memory is limited, arrays may be better.
If flexibility is important, linked lists may be better.

Let us look at some common operations.

Access in array:
Time complexity is O(1).
Access in linked list:
Time complexity is O(n).

Insertion at beginning of array:
Time complexity is O(n).
Insertion at beginning of linked list:
Time complexity is O(1).

Insertion at end of array:
Time complexity is O(1) amortized.
Insertion at end of linked list:
Time complexity is O(1) if tail is known.

Deletion in array:
Time complexity is O(n).
Deletion in linked list:
Time complexity is O(1) if node is known.

These differences are very important in design decisions.

Now consider real-world examples.

Arrays are used in:
Lists of items.
Matrices.
Buffers.
Static datasets.

Linked lists are used in:
Implementing stacks and queues.
Memory management systems.
Hash table chaining.
Graph adjacency lists.

In competitive programming, arrays are very common.
They are simple and fast.
In system programming, linked lists appear more often.

Understanding both helps you become a better developer.

Another important idea is iteration.
Iterating through an array is simple.
You use a loop from 0 to n-1.

Iterating through a linked list requires pointers.
You start at the head.
Then follow next pointers until null.

This makes linked lists slightly more complex to handle.

Now consider modification.
Changing an array element is easy.
You just assign a new value.

Changing a linked list node is also simple.
But finding the node may take time.

Let us discuss memory layout again.
Arrays use contiguous memory.
This improves performance in many cases.

Linked lists use non-contiguous memory.
This provides flexibility but reduces cache efficiency.

Now consider searching.
Searching in an unsorted array is O(n).
Searching in a linked list is also O(n).

So neither is better for searching unless sorted.
If sorted, arrays can use binary search.
Binary search is O(log n).

Linked lists cannot use binary search efficiently.
Because they do not support random access.

This gives arrays an advantage in sorted data.

Now consider resizing.
Arrays need resizing logic.
Linked lists do not need resizing.

Linked lists grow dynamically.
You can add nodes anytime.

This is useful when size is unknown.
But memory overhead increases.

Now think about implementation complexity.
Arrays are simple to use.
Linked lists require pointer handling.

Pointer mistakes can cause bugs.
Memory leaks can happen in some languages.

Garbage-collected languages reduce this problem.
But understanding memory is still important.

Now let us compare in summary.

Arrays:
Fast access.
Better cache performance.
Simple structure.
Expensive insertion and deletion.

Linked Lists:
Flexible size.
Efficient insertion and deletion.
Slower access.
More memory usage.

Both are important.
Neither is always better.

The best choice depends on the problem.
Good engineers choose based on requirements.

Understanding these structures helps in:
Interviews.
Algorithm design.
System optimization.
Real-world applications.

Students should practice both.
Implement arrays and linked lists manually.
Try inserting, deleting, and traversing.

This builds strong understanding.

This topic is foundational.
It appears in many advanced topics.
Stacks, queues, trees, and graphs build on these ideas.

A strong understanding here makes future topics easier.

That is why Arrays & Linked Lists are essential in data structures.
TEXT,

            'Stack & Queue' => <<<TEXT
Stack & Queue

Stack and Queue are fundamental linear data structures.
They are used to store and manage collections of elements.
They differ mainly in how elements are added and removed.

A stack follows the LIFO principle.
LIFO means Last In First Out.
The last element inserted is the first element removed.
This behavior is similar to a stack of plates.
You place plates on top and remove from the top.

The main operations of a stack are push, pop, and peek.
Push means adding an element to the top of the stack.
Pop means removing the top element from the stack.
Peek means viewing the top element without removing it.

Push operation is usually O(1).
Pop operation is also O(1).
Peek operation is O(1) as well.
This makes stack operations very efficient.

Stacks can be implemented using arrays.
Stacks can also be implemented using linked lists.
Both implementations provide similar behavior.

In an array-based stack, the top index is tracked.
When pushing, the index increases.
When popping, the index decreases.

In a linked list stack, the head acts as the top.
Push inserts a new node at the head.
Pop removes the head node.

Stacks are widely used in programming.
One important use is recursion.
The system uses a call stack to manage function calls.
Each function call is pushed onto the stack.
When a function finishes, it is popped from the stack.

Stacks are also used in undo and redo operations.
Each action is pushed onto the stack.
Undo removes the last action.
Redo may use another stack to restore actions.

Stacks are used in expression evaluation.
For example, evaluating mathematical expressions.
They help convert infix expressions to postfix or prefix.
They also help evaluate postfix expressions.

Stacks are used in syntax parsing.
Compilers use stacks to check balanced parentheses.
For example, expressions like ({[]}) are validated using stacks.

Stacks are useful whenever reverse order is needed.
They are simple but powerful structures.

Now let us discuss queues.

A queue follows the FIFO principle.
FIFO means First In First Out.
The first element inserted is the first element removed.
This behavior is similar to a line of people.
The first person in line is served first.

The main operations of a queue are enqueue, dequeue, and front.
Enqueue means adding an element to the rear of the queue.
Dequeue means removing an element from the front.
Front means viewing the first element without removing it.

Enqueue is usually O(1).
Dequeue is also O(1).
Front operation is O(1).

Queues can be implemented using arrays.
Queues can also be implemented using linked lists.

In array implementation, we use front and rear pointers.
Elements are added at the rear.
Elements are removed from the front.

A simple array queue may cause shifting issues.
Removing from the front may require shifting elements.
This can lead to O(n) operations.

To solve this, circular queues are used.
Circular queues reuse empty space.
They treat the array as circular.
This avoids unnecessary shifting.

In linked list queues, the head is the front.
The tail is the rear.
Enqueue adds at the tail.
Dequeue removes from the head.
This makes operations efficient.

Queues are used in many real-world systems.
One important use is task scheduling.
Operating systems use queues to manage processes.

Queues are used in buffering.
For example, data streaming uses queues.
Data is processed in the order it arrives.

Queues are used in printers.
Print jobs are processed in order.
The first job is printed first.

Queues are also used in networking.
Packets are processed in order of arrival.

Queues are essential in graph algorithms.
Breadth-First Search uses a queue.
It explores nodes level by level.
This ensures shortest path in unweighted graphs.

Queues are also used in simulations.
For example, customer service systems.
People are served in arrival order.

Now let us compare stack and queue.

Stack:
Uses LIFO.
Access is from one end only.
Main operations are push and pop.

Queue:
Uses FIFO.
Access is from two ends.
Main operations are enqueue and dequeue.

Stack is useful when reverse order matters.
Queue is useful when order must be preserved.

Both structures are simple but powerful.
They are building blocks for many algorithms.

There are also variations of these structures.

Deque is a double-ended queue.
It allows insertion and deletion from both ends.
It combines features of stack and queue.

Priority queue is another variation.
Elements are removed based on priority.
It is often implemented using heaps.

Now let us consider complexity.

Stack operations are usually constant time.
Queue operations are usually constant time.
This makes them efficient for real-time systems.

Now let us consider memory.

Array-based implementations use fixed or dynamic arrays.
Linked list implementations use extra memory for pointers.

Choosing between implementations depends on use case.
If memory locality matters, arrays may be better.
If flexibility matters, linked lists may be better.

Now let us discuss errors.

Stack overflow happens when stack is full.
Stack underflow happens when stack is empty and pop is called.

Queue overflow happens when queue is full.
Queue underflow happens when queue is empty and dequeue is called.

These conditions must be handled carefully.

Now let us discuss practical understanding.

Students should implement stack manually.
Students should implement queue manually.
This helps understand internal behavior.

Practice problems include:
Balanced parentheses.
Reverse a string using stack.
Implement queue using two stacks.
Implement stack using two queues.

These problems strengthen understanding.

Now let us reflect.

Stack and queue are simple.
But they appear everywhere in computing.
They help manage order and control flow.

Understanding them improves problem-solving skills.
They are essential for interviews.
They are essential for real-world systems.

A strong understanding of stack and queue leads to better algorithm design.
They are foundational tools every programmer must master.
TEXT,

            'Trees' => <<<TEXT
Trees

A tree is a hierarchical data structure.
It is made of nodes and edges.
Nodes represent data elements.
Edges represent connections between nodes.

The top node is called the root.
The root is the starting point of the tree.
Every tree has exactly one root.

Nodes may have children.
A child is a node connected below another node.
The node above is called the parent.

Nodes without children are called leaves.
Leaves represent the end points of the tree.
They do not have any further branches.

A tree does not contain cycles.
This means you cannot return to the same node by following edges.
This property makes trees different from graphs.

Trees are widely used in computer science.
They model hierarchical relationships.
Examples include file systems and organizational charts.

In a file system, folders and files form a tree.
The root directory is the top node.
Subfolders are children of the root.

Trees are also used in XML and HTML structures.
Each tag is a node.
Nested tags form child relationships.

Now let us discuss types of trees.

A binary tree is a tree where each node has at most two children.
These children are usually called left and right.
Binary trees are very common in algorithms.

A full binary tree is a tree where every node has either zero or two children.
A complete binary tree is filled level by level from left to right.
A perfect binary tree has all levels completely filled.

Now let us discuss Binary Search Trees.

A Binary Search Tree (BST) has a special property.
All values in the left subtree are smaller than the node.
All values in the right subtree are larger than the node.

This property allows efficient searching.
Searching in a balanced BST is O(log n).
Insertion and deletion can also be O(log n).

However, BST performance depends on balance.
If the tree becomes unbalanced, it may behave like a list.
In that case, operations may become O(n).

Balanced trees solve this issue.
Examples include AVL trees and Red-Black trees.
They maintain height balance automatically.

Now let us discuss tree traversal.

Traversal means visiting all nodes in a specific order.

Preorder traversal:
Visit the root first.
Then visit the left subtree.
Then visit the right subtree.

Inorder traversal:
Visit the left subtree.
Then visit the root.
Then visit the right subtree.
In a BST, inorder traversal gives sorted values.

Postorder traversal:
Visit the left subtree.
Then visit the right subtree.
Then visit the root.
This is useful for deleting trees.

Level-order traversal:
Visit nodes level by level.
This uses a queue.
It is also called Breadth-First Traversal.

Traversal is important for processing tree data.
Different problems require different traversal methods.

Now let us discuss tree height.

Height is the longest path from root to a leaf.
It determines the depth of the tree.
Height affects performance of operations.

A shallow tree is efficient.
A deep tree may be inefficient.

Now let us discuss insertion.

In a BST, insertion follows the BST rule.
If value is smaller, go left.
If value is larger, go right.
Insert when a null position is found.

Now let us discuss deletion.

Deletion in trees can be complex.
If the node is a leaf, remove it directly.
If the node has one child, replace it with the child.
If the node has two children, find inorder successor or predecessor.

Now let us discuss real-world uses.

Trees are used in databases.
Indexes are often implemented using tree structures.
This allows fast search and retrieval.

Trees are used in compilers.
Syntax trees represent program structure.
They help analyze and execute code.

Trees are used in search engines.
Data is indexed in hierarchical ways.

Trees are used in AI and decision making.
Decision trees help classify data.

Trees are used in networking.
Routing tables can use tree-like structures.

Now let us discuss advantages.

Trees allow hierarchical representation.
They support efficient search.
They are flexible and widely applicable.

Now let us discuss disadvantages.

Trees can become unbalanced.
Unbalanced trees reduce efficiency.
Implementation can be complex.

Now let us compare trees with other structures.

Arrays are linear.
Linked lists are linear.
Trees are hierarchical.

Trees allow faster search than lists.
Trees allow structured data representation.

Now let us discuss complexity.

Searching in balanced BST is O(log n).
Insertion in balanced BST is O(log n).
Deletion in balanced BST is O(log n).

Traversal is O(n).
All nodes must be visited once.

Now let us discuss memory.

Each node stores data and pointers.
Memory usage is higher than arrays.
But structure provides flexibility.

Now let us discuss recursion.

Tree operations are often recursive.
Each subtree is itself a tree.
This makes recursion natural for trees.

Now let us discuss practice.

Students should implement trees manually.
Try insertion and traversal.
Try searching values.

Practice problems include:
Find height of tree.
Check if tree is balanced.
Find lowest common ancestor.
Traverse tree in different orders.

These problems build understanding.

Now let us reflect.

Trees are one of the most important data structures.
They appear in many systems.
They help organize and search data efficiently.

Understanding trees opens the door to advanced topics.
These include heaps, graphs, and tries.

Trees are essential for both theory and practice.
Mastering trees improves problem-solving ability.
They are a core concept every programmer must understand.
TEXT,

            'Heap & Priority Queue' => <<<TEXT
Heap & Priority Queue

A heap is a special type of binary tree.
It is a complete binary tree.
A complete binary tree is filled level by level.
All levels are filled except possibly the last.
The last level is filled from left to right.

Heaps satisfy a specific property.
This is called the heap property.
There are two main types of heaps.

A max-heap ensures the largest value is at the root.
Every parent node is greater than or equal to its children.

A min-heap ensures the smallest value is at the root.
Every parent node is less than or equal to its children.

Heaps are not fully sorted structures.
Only the root is guaranteed to be the maximum or minimum.
Other elements follow partial ordering rules.

Heaps are usually implemented using arrays.
This makes them very efficient in memory.
There is no need for explicit pointers.

In array representation:
The root is at index 0.
For a node at index i:
Left child is at 2i + 1.
Right child is at 2i + 2.
Parent is at (i - 1) / 2.

This formula makes navigation easy.
It avoids the overhead of node objects.

Now let us discuss operations.

Insertion in a heap:
Insert the element at the end.
Then move it up the tree.
This is called heapify up or sift up.
It restores the heap property.

Insertion takes O(log n).
Because the height of the tree is log n.

Extraction (removing root):
Remove the root element.
Replace it with the last element.
Then move it down the tree.
This is called heapify down or sift down.

Extraction also takes O(log n).
Because we may move down the height of the tree.

Peek operation:
Return the root element.
This is O(1).
No movement is required.

Building a heap:
You can insert elements one by one.
This takes O(n log n).
But there is a faster method.

Heapify from bottom:
Start from the last non-leaf node.
Apply heapify down.
This builds the heap in O(n) time.

This is an important optimization.

Now let us discuss priority queues.

A priority queue is a data structure.
It removes elements based on priority.
Not based on insertion order.

In a normal queue:
First in, first out.

In a priority queue:
Highest priority comes out first.

If using a max-heap:
The largest value has highest priority.

If using a min-heap:
The smallest value has highest priority.

Priority queues are often implemented using heaps.
Because heaps provide efficient operations.

Insert: O(log n)
Remove highest priority: O(log n)
Peek: O(1)

Now let us look at real-world uses.

Task scheduling:
Operating systems use priority queues.
High-priority tasks run first.

Graph algorithms:
Dijkstra’s algorithm uses a priority queue.
It always selects the node with smallest distance.

Prim’s algorithm also uses priority queues.
It builds minimum spanning trees.

Top-K problems:
Find largest k elements.
Heap helps maintain top k efficiently.

Streaming data:
Heaps help process continuous data.
You can track minimum or maximum dynamically.

Heaps are used in sorting.
Heap sort is a famous algorithm.

Heap sort steps:
Build a heap.
Extract elements one by one.
This produces a sorted array.

Heap sort has O(n log n) complexity.
It does not require extra space.
It is an in-place algorithm.

Now let us discuss advantages.

Heaps are efficient.
They provide fast insertion and removal.
They use simple array representation.

They are ideal for priority-based problems.
They are widely used in algorithms.

Now let us discuss disadvantages.

Heaps do not support fast searching.
Searching for arbitrary elements is O(n).
They are not fully sorted structures.

If full sorting is needed, other structures may be better.

Now let us compare heaps and BST.

Heap:
Only root is ordered.
Fast access to min or max.
Better for priority tasks.

BST:
Fully ordered structure.
Better for searching specific values.
Supports ordered traversal.

Now let us discuss memory.

Heaps use arrays.
This makes them memory efficient.
No extra pointer storage is needed.

Now let us discuss tree height.

Heap height is log n.
This ensures efficient operations.

Balanced structure is guaranteed.
Unlike BST, heap never becomes skewed.

Now let us discuss applications in coding.

Common interview problems use heaps.
Examples:
Find kth largest element.
Merge k sorted lists.
Find median in a stream.

These problems rely on heap behavior.

Now let us discuss practice.

Students should implement heap manually.
Practice insertion and deletion.
Understand array representation clearly.

Try coding:
Min heap
Max heap
Priority queue

Practice problems improve understanding.

Now let us reflect.

Heaps are simple but powerful.
They solve priority-based problems efficiently.
They are widely used in real systems.

Understanding heaps improves algorithm skills.
Understanding priority queues improves design thinking.

Heap & Priority Queue is an essential topic.
It connects trees, arrays, and algorithms together.
Mastering it is important for every programmer.
TEXT,

            'Graphs' => <<<TEXT
Graphs

A graph is a data structure used to represent relationships.
It is made of vertices and edges.
Vertices are also called nodes.
Edges represent connections between nodes.

Graphs are very flexible structures.
They can represent many real-world systems.
Examples include social networks and maps.

Graphs can be directed or undirected.
In a directed graph, edges have direction.
An edge goes from one node to another.
This means the relationship is one-way.

In an undirected graph, edges have no direction.
If A is connected to B, then B is connected to A.
This means the relationship is two-way.

Graphs can also be weighted or unweighted.
In weighted graphs, edges have values.
These values may represent distance or cost.
In unweighted graphs, edges have no values.

Graphs are used in many applications.
They are used in navigation systems.
They are used in network routing.
They are used in recommendation systems.

Graphs are used to model dependencies.
For example, tasks in a project.
Some tasks depend on others.
This can be represented as a graph.

Now let us discuss graph traversal.

Traversal means visiting all nodes.
There are two main traversal methods.

Breadth-First Search (BFS):
BFS explores level by level.
It uses a queue.
It starts from a node.
Then visits all neighbors.
Then visits neighbors of neighbors.

BFS is useful for shortest path in unweighted graphs.
It guarantees the shortest number of edges.

Depth-First Search (DFS):
DFS explores deeply.
It goes as far as possible before backtracking.
It uses recursion or a stack.

DFS is useful for exploring all possibilities.
It is used in cycle detection.
It is also used in topological sorting.

Now let us discuss graph representation.

Adjacency Matrix:
It uses a 2D array.
If there is an edge, value is 1.
If not, value is 0.

Matrix uses O(V^2) memory.
It is simple and easy to understand.
It is good for dense graphs.

Adjacency List:
Each node has a list of neighbors.
It uses less memory.
It is O(V + E).

Adjacency list is better for sparse graphs.
Most real-world graphs are sparse.

Now let us discuss complexity.

BFS complexity is O(V + E).
DFS complexity is also O(V + E).
This means each node and edge is visited once.

Now let us discuss paths.

A path is a sequence of edges.
It connects one node to another.
Shortest path is important in many problems.

In unweighted graphs, BFS finds shortest path.
In weighted graphs, we use Dijkstra algorithm.

Now let us discuss cycles.

A cycle is a path that starts and ends at same node.
Cycles can cause problems in some systems.
For example, dependency cycles.

DFS is often used to detect cycles.
We track visited nodes.
We also track recursion stack.

Now let us discuss connected components.

A connected graph means all nodes are reachable.
A disconnected graph has separate parts.
Each part is called a component.

We can use DFS or BFS to find components.

Now let us discuss DAG.

DAG means Directed Acyclic Graph.
It is a directed graph with no cycles.
It is used in scheduling and dependency resolution.

Topological sorting works on DAG.
It orders nodes so dependencies are satisfied.

Now let us discuss real-world examples.

Road networks:
Nodes are cities.
Edges are roads.
Weights may represent distance.

Social networks:
Nodes are people.
Edges are friendships.

Web pages:
Nodes are pages.
Edges are links.

Computer networks:
Nodes are devices.
Edges are connections.

Graphs are everywhere.

Now let us discuss advantages.

Graphs are very flexible.
They can represent complex relationships.
They support many algorithms.

Now let us discuss disadvantages.

Graphs can be complex to implement.
Memory usage can be high.
Algorithms can be difficult.

Now let us discuss practice.

Students should implement graphs.
Try adjacency list representation.
Try BFS and DFS.

Practice problems include:
Find shortest path.
Detect cycle.
Count components.
Topological sort.

These problems build strong understanding.

Now let us reflect.

Graphs are one of the most powerful data structures.
They are used in many advanced systems.
Understanding graphs improves problem-solving skills.

Graphs connect many topics together.
They combine ideas from trees, recursion, and search.

Mastering graphs is essential for advanced programming.
They are a core concept in computer science.
TEXT,

            'Hashing' => <<<TEXT
Hashing

Hashing is a technique used to map data to specific locations.
It is widely used in computer science for fast data access.
The main idea is to convert a key into an index.
This index is used to store or retrieve data from a table.

The structure that uses hashing is called a hash table.
A hash table stores key-value pairs.
Each key is processed by a hash function.
The hash function produces an index.

The index determines where the value is stored.
This allows very fast operations.
Lookup is usually O(1) on average.
Insertion is also O(1) on average.
Deletion is also O(1) on average.

This makes hashing very efficient.
It is much faster than linear search in many cases.
It is widely used in real-world applications.

Now let us understand hash functions.

A hash function takes an input key.
It converts the key into a number.
This number is used as an index.

A good hash function has important properties.
It should be fast to compute.
It should distribute keys evenly.
It should minimize collisions.

A bad hash function causes problems.
It may map many keys to the same index.
This reduces performance.

Now let us understand collisions.

A collision happens when two keys produce the same index.
For example:
Key A → index 5
Key B → index 5

This creates a conflict.
We need a way to handle it.

There are two main methods.

Separate Chaining:
Each index stores a list of elements.
When collision happens, we add to the list.
This list can be a linked list or dynamic array.

Search in chaining:
If list is small, performance is good.
If list becomes long, performance decreases.

Open Addressing:
All elements are stored in the same array.
When collision happens, we find another position.
This is done using probing techniques.

Linear Probing:
Move to next index.
If occupied, keep moving.

Quadratic Probing:
Jump in quadratic steps.
Reduces clustering.

Double Hashing:
Use a second hash function.
Provides better distribution.

Now let us discuss load factor.

Load factor = number of elements / table size.
It measures how full the table is.

If load factor is low:
Performance is good.
Few collisions occur.

If load factor is high:
Collisions increase.
Performance decreases.

To solve this, we use rehashing.

Rehashing increases table size.
All elements are reinserted.
New hash values are calculated.

Rehashing improves distribution.
It reduces collisions.

Now let us discuss complexity.

Average case:
Search O(1)
Insert O(1)
Delete O(1)

Worst case:
Search O(n)
Insert O(n)
Delete O(n)

Worst case happens when collisions are many.
This depends on hash function quality.

Now let us discuss memory.

Hash tables use arrays.
They may waste space if too large.
They may require resizing.

Memory trade-off is common.
We use more space for faster access.

Now let us discuss real-world uses.

Hash tables are used in dictionaries.
Key → value mapping.

They are used in sets.
To store unique elements.

They are used in caching.
Fast lookup of stored data.

They are used in databases.
Indexing improves query speed.

They are used in compilers.
Symbol tables use hashing.

They are used in duplicate detection.
Check if element exists quickly.

Now let us discuss advantages.

Very fast operations.
Simple concept.
Widely supported in languages.

Now let us discuss disadvantages.

Performance depends on hash function.
Collisions reduce efficiency.
Memory usage may increase.

Now let us compare hashing with other structures.

Array:
Fast access but fixed index.

Linked List:
Flexible but slow search.

Tree:
Sorted structure but slower than hash in average case.

Hash Table:
Best average performance for lookup.

Now let us discuss design considerations.

Choose good hash function.
Maintain good load factor.
Resize when needed.
Handle collisions properly.

Now let us discuss practical examples.

Check if word exists:
Use hash set.

Count frequency:
Use hash map.

Remove duplicates:
Use hash table.

Store user sessions:
Use hashing for fast lookup.

Now let us discuss common mistakes.

Using poor hash function.
Ignoring collisions.
Not resizing table.
Using too high load factor.

These reduce performance.

Now let us discuss learning strategy.

Students should implement hash table manually.
Try chaining and open addressing.
Understand collision handling deeply.

Practice problems include:
Two sum problem.
Find duplicates.
Group anagrams.
Count frequencies.

These problems build understanding.

Now let us reflect.

Hashing is one of the most practical ideas in programming.
It provides fast data access.
It is used in almost every system.

Understanding hashing improves coding performance.
It helps in solving many problems efficiently.

Hashing connects theory with real-world usage.
It is a must-know topic for every programmer.
TEXT,

            default => <<<TEXT
General Topic

This lesson explains the topic in a simple and focused way.
It introduces the main definition, key ideas, common examples, and practical usage.
Students should read the explanation carefully and then practice topic-specific questions.
TEXT,
        };
    }
}